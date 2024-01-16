<?php

namespace Intervention\Image\Modifiers;

class SliceAnimationModifier extends SpecializableModifier
{
    public function __construct(public int $offset = 0, public ?int $length = null)
    {
    }
}
