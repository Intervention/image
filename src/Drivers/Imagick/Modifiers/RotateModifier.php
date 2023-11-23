<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class RotateModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->driver()->colorToNative(
            $this->driver()->handleInput($this->background),
            $image->colorspace()
        );

        foreach ($image as $frame) {
            $frame->native()->rotateImage(
                $background,
                $this->rotationAngle() * -1
            );
        }

        return $image;
    }
}
