<?php

namespace Intervention\Image\Modifiers;

class BrightnessModifier extends AbstractModifier
{
    public function __construct(public int $level)
    {
    }
}
