<?php

namespace Intervention\Image\Modifiers;

class ResolutionModifier extends AbstractModifier
{
    public function __construct(public float $x, public float $y)
    {
    }
}
