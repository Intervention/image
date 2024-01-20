<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;

class DrawRectangleModifier extends SpecializableModifier
{
    public function __construct(public Rectangle $drawable)
    {
    }
}
