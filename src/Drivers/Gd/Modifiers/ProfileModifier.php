<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ProfileInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ProfileModifier as GenericProfileModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

/**
 * @property ProfileInterface $profile
 */
class ProfileModifier extends GenericProfileModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function apply(ImageInterface $image): ImageInterface
    {
        throw new NotSupportedException(
            'Color profiles are not supported by GD driver.'
        );
    }
}
