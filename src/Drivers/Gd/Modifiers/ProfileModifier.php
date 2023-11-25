<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ProfileInterface;

/**
 * @property ProfileInterface $profile
 */
class ProfileModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        throw new NotSupportedException(
            'Color profiles are not supported by GD driver.'
        );
    }
}
