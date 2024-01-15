<?php

namespace Intervention\Image\Modifiers;

class SliceAnimationModifier extends AbstractModifier
{
    public function __construct(public int $offset = 0, public ?int $length = null)
    {
    }
}
