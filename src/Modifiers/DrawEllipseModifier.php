<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Ellipse;

class DrawEllipseModifier extends SpecializableModifier
{
    public function __construct(public Ellipse $drawable)
    {
    }
}
