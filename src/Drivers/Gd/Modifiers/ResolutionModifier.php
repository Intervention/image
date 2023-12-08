<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $x
 * @property int $y
 */
class ResolutionModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $x = intval(round($this->x));
        $y = intval(round($this->y));

        foreach ($image as $frame) {
            imageresolution($frame->native(), $x, $y);
        }

        return $image;
    }
}
