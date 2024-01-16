<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

class BlurModifier extends SpecializableModifier
{
    public function __construct(public int $amount)
    {
    }
}
