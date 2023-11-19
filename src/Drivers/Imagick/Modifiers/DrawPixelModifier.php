<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
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

        $pixel = new ImagickDraw();
        $pixel->setFillColor($color);
        $pixel->point($this->position->x(), $this->position->y());

        foreach ($image as $frame) {
            $frame->data()->drawImage($pixel);
        }

        return $image;
    }
}
