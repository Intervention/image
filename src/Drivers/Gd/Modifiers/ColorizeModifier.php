<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ColorizeModifier as GenericColorizeModifier;

class ColorizeModifier extends GenericColorizeModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // normalize colorize levels
        $red = (int) round($this->red * 2.55);
        $green = (int) round($this->green * 2.55);
        $blue = (int) round($this->blue * 2.55);

        foreach ($image as $frame) {
            $result = imagefilter($frame->native(), IMG_FILTER_COLORIZE, $red, $green, $blue);
            if ($result === false) {
                throw new ModifierException('Failed to apply colorize effect');
            }
        }

        return $image;
    }
}
