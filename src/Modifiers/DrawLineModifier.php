<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Line;

class DrawLineModifier extends SpecializableModifier
{
    public function __construct(public Line $drawable)
    {
    }
}
