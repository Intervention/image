<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @method mixed rotationAngle()
 * @property mixed $background
 */
class RotateModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
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
