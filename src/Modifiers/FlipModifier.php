<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Direction;
use Intervention\Image\Drivers\SpecializableModifier;

class FlipModifier extends SpecializableModifier
{
    public function __construct(public Direction $direction = Direction::HORIZONTAL)
    {
        //
    }
}
