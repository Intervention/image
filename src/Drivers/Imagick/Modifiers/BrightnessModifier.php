<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $level
 */
class BrightnessModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->modulateImage(100 + $this->level, 100, 100);
        }

        return $image;
    }
}
