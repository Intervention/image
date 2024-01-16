<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Point;

class FillModifier extends SpecializableModifier
{
    public function __construct(
        public mixed $color,
        public ?Point $position = null
    ) {
    }

    public function hasPosition(): bool
    {
        return !empty($this->position);
    }
}
