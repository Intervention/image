<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;

/**
 * @method bool hasPosition()
 * @property mixed $filling
 * @property null|Point $position
 */
class FillModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $filling = $this->resolveFilling($image);
        $call = $this->hasPosition() ? 'floodFill' : 'fillAll';
        $call .= is_a($filling, ImagickPixel::class) ? 'WithColor' : 'WithImage';

        foreach ($image as $frame) {
            $frame->setNative(
                call_user_func([$this, $call], $frame, $filling)
            );
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

    private function floodFillWithImage(Frame $frame, Imagick $texture): Imagick
    {
        // create tile
        $tile = clone $frame->native();

        // mask away color at position
        $tile->transparentPaintImage(
            $tile->getImagePixelColor($this->position->x(), $this->position->y()),
            0,
            0,
            false,
        );

        // create canvas
        $canvas = clone $frame->native();

        // fill canvas with texture
        $canvas = $canvas->textureImage($texture);

        // merge canvas and tile
        $canvas->compositeImage($tile, Imagick::COMPOSITE_DEFAULT, 0, 0);

        return $canvas;
    }

    private function fillAllWithImage(Frame $frame, Imagick $texture): Imagick
    {
        return $frame->native()->textureImage($texture);
    }
}
