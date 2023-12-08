<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $level
 */
class BrightnessModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagefilter($frame->native(), IMG_FILTER_BRIGHTNESS, intval($this->level * 2.55));
        }

        return $image;
    }
}
