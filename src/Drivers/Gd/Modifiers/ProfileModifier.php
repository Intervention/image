<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\ProfileInterface;

/**
 * @property ProfileInterface $profile
 */
class ProfileModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        throw new NotSupportedException(
            'Color profiles are not supported by GD driver.'
        );
    }
}
