<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class DrawPixelModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->colorToNative(
            $this->driver()->handleInput($this->color),
            $image->colorspace()
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
