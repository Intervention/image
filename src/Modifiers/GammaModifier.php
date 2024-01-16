<?php

namespace Intervention\Image\Modifiers;

class GammaModifier extends SpecializableModifier
{
    public function __construct(public float $gamma)
    {
        //
    }
}
