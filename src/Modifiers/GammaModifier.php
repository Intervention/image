<?php

namespace Intervention\Image\Modifiers;

class GammaModifier extends AbstractModifier
{
    public function __construct(public float $gamma)
    {
        //
    }
}
