<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Polygon;

class DrawPolygonModifier extends SpecializableModifier
{
    public function __construct(public Polygon $drawable)
    {
    }
}
