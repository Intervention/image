<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;

class DrawRectangleModifier extends AbstractModifier
{
    public function __construct(public Rectangle $drawable)
    {
    }
}
