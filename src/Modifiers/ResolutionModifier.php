<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

class ResolutionModifier extends SpecializableModifier
{
    public function __construct(public float $x, public float $y)
    {
    }
}
