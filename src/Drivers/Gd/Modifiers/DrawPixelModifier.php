<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\PointInterface;

/**
 * @property PointInterface $position
 * @property mixed $color
 */
class DrawPixelModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->color)
        );

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imagesetpixel(
                $frame->native(),
                $this->position->x(),
                $this->position->y(),
                $color
            );
        }

        return $image;
    }
}
