<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Ellipse;

class DrawEllipseModifier extends SpecializableModifier
{
    public function __construct(public Ellipse $drawable)
    {
    }
}
