<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Polygon;

class DrawPolygonModifier extends SpecializableModifier
{
    public function __construct(public Polygon $drawable)
    {
    }
}
