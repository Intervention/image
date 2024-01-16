<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Interfaces\PointInterface;

class DrawPixelModifier extends SpecializableModifier
{
    public function __construct(
        public PointInterface $position,
        public mixed $color
    ) {
    }
}
