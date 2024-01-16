<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @method ColorspaceInterface targetColorspace()
 */
class ColorspaceModifier extends DriverSpecialized implements ModifierInterface
{
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
