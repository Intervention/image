<?php

namespace Intervention\Image\Modifiers;

class PixelateModifier extends AbstractModifier
{
    public function __construct(public int $size)
    {
    }
}
