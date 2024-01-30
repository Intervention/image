<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property int $size
 */
class PixelateModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $this->pixelateFrame($frame);
        }

        return $image;
    }

    protected function pixelateFrame(Frame $frame): void
    {
        $size = $frame->size();

        $frame->native()->scaleImage(
            (int) round(max(1, $size->width() / $this->size)),
            (int) round(max(1, $size->height() / $this->size))
        );

        $frame->native()->scaleImage($size->width(), $size->height());
    }
}
