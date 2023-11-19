<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Interfaces\PointInterface;

class DrawPixelModifier extends AbstractModifier
{
    public function __construct(
        public PointInterface $position,
        public mixed $color
    ) {
    }
}
