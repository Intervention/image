<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Interfaces\ProfileInterface;

class ProfileModifier extends SpecializableModifier
{
    public function __construct(public ProfileInterface $profile)
    {
    }
}
