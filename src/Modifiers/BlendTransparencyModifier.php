<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class BlendTransparencyModifier extends SpecializableModifier
{
    public function __construct(public mixed $color = null)
    {
    }
}
