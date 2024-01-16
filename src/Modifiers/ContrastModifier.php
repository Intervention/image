<?php

namespace Intervention\Image\Modifiers;

class ContrastModifier extends SpecializableModifier
{
    public function __construct(public int $level)
    {
    }
}
