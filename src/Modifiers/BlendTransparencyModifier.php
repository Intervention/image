<?php

namespace Intervention\Image\Modifiers;

class BlendTransparencyModifier extends SpecializableModifier
{
    public function __construct(public mixed $color = null)
    {
    }
}
