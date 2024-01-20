<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

class PixelateModifier extends SpecializableModifier
{
    public function __construct(public int $size)
    {
    }
}
