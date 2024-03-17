<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPixelModifier as GenericDrawPixelModifier;

class DrawPixelModifier extends GenericDrawPixelModifier implements SpecializedInterface
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
