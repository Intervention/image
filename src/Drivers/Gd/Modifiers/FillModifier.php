<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
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

        foreach ($image as $frame) {
            if (is_int($filling)) {
                $this->fillWithColor($frame, $filling);
            } else {
                $this->fillWithImage($frame, $filling);
            }
        }

        return $image;
    }

    /**
     * Resolve filling to its native version which can either be a
     * color (integer) or an image (GdImage)
     *
     * @param ImageInterface $image
     * @return GdImage|int
     */
    private function resolveFilling(ImageInterface $image): int|GdImage
    {
        $filling = $this->driver()->handleInput($this->filling);

        return match (true) {
            $filling instanceof ColorInterface => $this->driver()
                ->colorProcessor($image->colorspace())
                ->colorToNative($filling),
            default => $filling->core()->native(),
        };
    }

    /**
     * Fill frame with given color
     *
     * @param Frame $frame
     * @param int $color
     * @return void
     */
    private function fillWithColor(Frame $frame, int $color): void
    {
        if ($this->hasPosition()) {
            // flood fill if position is set
            imagefill(
                $frame->native(),
                $this->position->x(),
                $this->position->y(),
                $color
            );
        } else {
            // fill image completely if no position is set
            imagealphablending($frame->native(), true);
            imagefilledrectangle(
                $frame->native(),
                0,
                0,
                $frame->size()->width() - 1,
                $frame->size()->height() - 1,
                $color
            );
        }
    }

    /**
     * Fill frame with given image texture
     *
     * @param Frame $frame
     * @param GdImage $gd
     * @return void
     */
    private function fillWithImage(Frame $frame, GdImage $gd): void
    {
        imagealphablending($frame->native(), true);

        imagesettile($frame->native(), $gd);
        $filling = IMG_COLOR_TILED;

        $width = imagesx($frame->native());
        $height = imagesy($frame->native());

        // flood fill if position is set
        if ($this->hasPosition()) {
            // create new image
            $base = Cloner::clone($frame->native());

            // flood fill at exact position
            imagefill($frame->native(), $this->position->x(), $this->position->y(), $filling);

            // copy filled original over base
            imagecopy($base, $frame->native(), 0, 0, 0, 0, $width, $height);

            // set base as new resource-core
            $frame->setNative($base);
        } else {
            // fill image completely if no position is set
            imagefilledrectangle($frame->native(), 0, 0, $width - 1, $height - 1, $filling);
        }
    }
}
