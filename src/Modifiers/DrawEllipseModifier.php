<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Ellipse;

class DrawEllipseModifier extends AbstractModifier
{
    public function __construct(public Ellipse $drawable)
    {
    }
}
