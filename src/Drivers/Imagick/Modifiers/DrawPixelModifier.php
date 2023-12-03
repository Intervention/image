<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
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

        $pixel = new ImagickDraw();
        $pixel->setFillColor($color);
        $pixel->point($this->position->x(), $this->position->y());

        foreach ($image as $frame) {
            $frame->native()->drawImage($pixel);
        }

        return $image;
    }
}
