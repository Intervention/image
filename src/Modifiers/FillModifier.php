<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\PointInterface;

class FillModifier extends SpecializableModifier
{
    public function __construct(
        public mixed $color,
        public ?PointInterface $position = null
    ) {
    }

    public function hasPosition(): bool
    {
        return $this->position instanceof PointInterface;
    }
}
