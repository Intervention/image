<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;

class ResizeModifier extends SpecializableModifier
{
    public function __construct(public ?int $width = null, public ?int $height = null)
    {
    }
}
