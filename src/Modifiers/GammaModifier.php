<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

class GammaModifier extends SpecializableModifier
{
    public function __construct(public float $gamma)
    {
        //
    }
}
