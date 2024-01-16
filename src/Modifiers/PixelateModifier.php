<?php

namespace Intervention\Image\Modifiers;

class PixelateModifier extends SpecializableModifier
{
    public function __construct(public int $size)
    {
    }
}
