<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class TrimModifier extends SpecializableModifier
{
    public function __construct(public int $tolerance = 0)
    {
    }
}
