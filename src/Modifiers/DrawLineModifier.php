<?php

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Line;

class DrawLineModifier extends SpecializableModifier
{
    public function __construct(public Line $drawable)
    {
    }
}
