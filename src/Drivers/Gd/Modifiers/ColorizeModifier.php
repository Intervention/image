<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property int $red
 * @property int $green
 * @property int $blue
 */
class ColorizeModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // normalize colorize levels
        $red = round($this->red * 2.55);
        $green = round($this->green * 2.55);
        $blue = round($this->blue * 2.55);

        foreach ($image as $frame) {
            imagefilter($frame->native(), IMG_FILTER_COLORIZE, $red, $green, $blue);
        }

        return $image;
    }
}
