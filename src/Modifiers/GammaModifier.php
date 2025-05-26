<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class GammaModifier extends SpecializableModifier
{
    public function __construct(public float $gamma)
    {
        //
    }
}
