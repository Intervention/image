<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ColorizeModifier as GenericColorizeModifier;

class ColorizeModifier extends GenericColorizeModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // normalize colorize levels
        $red = (int) round($this->red * 2.55);
        $green = (int) round($this->green * 2.55);
        $blue = (int) round($this->blue * 2.55);

        foreach ($image as $frame) {
            imagefilter($frame->native(), IMG_FILTER_COLORIZE, $red, $green, $blue);
        }

        return $image;
    }
}
