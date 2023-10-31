<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ColorizeModifier implements ModifierInterface
{
    public function __construct(
        protected int $red = 0,
        protected int $green = 0,
        protected int $blue = 0
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        // normalize colorize levels
        $red = round($this->red * 2.55);
        $green = round($this->green * 2.55);
        $blue = round($this->blue * 2.55);

        foreach ($image as $frame) {
            imagefilter($frame->core(), IMG_FILTER_COLORIZE, $red, $green, $blue);
        }

        return $image;
    }
}
