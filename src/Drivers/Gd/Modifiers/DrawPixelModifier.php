<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PointInterface;

/**
 * @property PointInterface $position
 * @property mixed $color
 */
class DrawPixelModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->color)
        );

        foreach ($image as $frame) {
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
