<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

class BrightnessModifier extends SpecializableModifier
{
    public function __construct(public int $level)
    {
    }
}
