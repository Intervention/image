<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use ImagickPixelException;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @method bool hasPosition()
 * @property mixed $filling
 * @property null|Point $position
 */
class FillModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $filling = $this->resolveFilling($image);
        $call = $this->hasPosition() ? 'floodFill' : 'fillAll';
        $call .= is_a($filling, ImagickPixel::class) ? 'WithColor' : 'WithImage';

        foreach ($image as $frame) {
            call_user_func([$this, $call], $frame, $filling);
        }

        return $image;
    }

    /**
     * Resolve filling to its native version which can either be a
     * color (ImagickPixel) or an image (Imagick)
     *
     * @param ImageInterface $image
     * @return ImagickPixel|Imagick
     */
    private function resolveFilling(ImageInterface $image): ImagickPixel|Imagick
    {
        $filling = $this->driver()->handleInput($this->filling);

        return match (true) {
            $filling instanceof ImageInterface => $filling->core()->native(),
            default => $this->driver()
                ->colorProcessor($image->colorspace())
                ->colorToNative($filling),
        };
    }

    /**
     * Modify given frame by flood filling with given color at the modifier's position
     *
     * @param Frame $frame
     * @param ImagickPixel $pixel
     * @return Imagick
     * @throws ImagickException
     */
    private function floodFillWithColor(Frame $frame, ImagickPixel $pixel): Imagick
    {
        $target = $frame->native()->getImagePixelColor(
            $this->position->x(),
            $this->position->y()
        );

        $frame->native()->floodfillPaintImage(
            $pixel,
            100,
            $target,
            $this->position->x(),
            $this->position->y(),
            false,
            Imagick::CHANNEL_ALL
        );

        return $frame->native();
    }

    /**
     * Modify given frame by filling it completely with given color
     *
     * @param Frame $frame
     * @param ImagickPixel $pixel
     * @return Imagick
     * @throws ImagickDrawException
     * @throws ImagickException
     */
    private function fillAllWithColor(Frame $frame, ImagickPixel $pixel): Imagick
    {
        $draw = new ImagickDraw();
        $draw->setFillColor($pixel);

        $draw->rectangle(
            0,
            0,
            $frame->native()->getImageWidth(),
            $frame->native()->getImageHeight()
        );

        $frame->native()->drawImage($draw);

        return $frame->native();
    }

    /**
     * Modify given frame by flood filling it with given texture at the modifier's position
     *
     * @param Frame $frame
     * @param Imagick $texture
     * @return void
     * @throws ImagickException
     * @throws ImagickPixelException
     */
    private function floodFillWithImage(Frame $frame, Imagick $texture): void
    {
        // create tile
        $tile = clone $frame->native();

        // get color at position
        $targetColor = $tile->getImagePixelColor($this->position->x(), $this->position->y());

        // mask away color at position
        // does not work becaue there might be other transparent areas
        $tile->transparentPaintImage($targetColor, 0, 0, false);

        // fill canvas with texture
        $canvas = $frame->native()->textureImage($texture);

        // merge canvas and tile
        $canvas->compositeImage($tile, Imagick::COMPOSITE_DEFAULT, 0, 0);

        // copy original alpha channel only if position is not completely transparent
        if ($targetColor->getColorValue(Imagick::COLOR_ALPHA) != 0) {
            $canvas->compositeImage($frame->native(), Imagick::COMPOSITE_DSTIN, 0, 0);
        }

        // replace imagick of frame
        $frame->native()->compositeImage($canvas, Imagick::COMPOSITE_SRCOVER, 0, 0);
    }

    /**
     * Fill given frame completely with given texture
     *
     * @param Frame $frame
     * @param Imagick $texture
     * @return void
     * @throws ImagickException
     */
    private function fillAllWithImage(Frame $frame, Imagick $texture): void
    {
        // fill completely with texture
        $modified = $frame->native()->textureImage($texture);

        // replace imagick of frame
        $frame->native()->compositeImage($modified, Imagick::COMPOSITE_SRCOVER, 0, 0);
    }
}
