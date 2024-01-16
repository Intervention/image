<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

class SharpenModifier extends SpecializableModifier
{
    public function __construct(public int $amount)
    {
    }
}
