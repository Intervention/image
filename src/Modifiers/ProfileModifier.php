<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Interfaces\ProfileInterface;

class ProfileModifier extends AbstractModifier
{
    public function __construct(public ProfileInterface $profile)
    {
    }
}
