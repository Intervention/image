<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

class FlipModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->flipImage();
        }

        return $image;
    }
}
