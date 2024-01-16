<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Interfaces\ProfileInterface;

class ProfileModifier extends SpecializableModifier
{
    public function __construct(public ProfileInterface $profile)
    {
    }
}
