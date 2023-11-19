<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Polygon;

class DrawPolygonModifier extends AbstractModifier
{
    public function __construct(public Polygon $drawable)
    {
    }
}
