<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ColorspaceModifier as GenericColorspaceModifier;

class ColorspaceModifier extends GenericColorspaceModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if (!is_a($this->targetColorspace(), RgbColorspace::class)) {
            throw new NotSupportedException(
                'Only RGB colorspace is supported by GD driver.'
            );
        }

        return $image;
    }
}
