<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;

class ResolutionModifier extends DriverModifier
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
