<?php

namespace Intervention\Image\Modifiers;

class BlendTransparencyModifier extends AbstractModifier
{
    public function __construct(public mixed $color = null)
    {
    }
}
