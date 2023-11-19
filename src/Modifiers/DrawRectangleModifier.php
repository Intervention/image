<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\PointInterface;

class DrawRectangleModifier extends AbstractModifier
{
    public function __construct(
        public PointInterface $position,
        public Rectangle $drawable,
    ) {
    }
}
