<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\PointInterface;

class DrawPixelModifier extends SpecializableModifier
{
    public function __construct(
        public PointInterface $position,
        public mixed $color
    ) {
    }
}
