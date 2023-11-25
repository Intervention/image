<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @method ColorspaceInterface targetColorspace()
 */
class ColorspaceModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        if (!is_a($this->targetColorspace(), RgbColorspace::class)) {
            throw new NotSupportedException(
                'Only RGB colorspace is supported with GD driver.'
            );
        }

        return $image;
    }
}
