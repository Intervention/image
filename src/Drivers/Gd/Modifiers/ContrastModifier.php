<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $level
 */
class ContrastModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagefilter($frame->native(), IMG_FILTER_CONTRAST, ($this->level * -1));
        }

        return $image;
    }
}
