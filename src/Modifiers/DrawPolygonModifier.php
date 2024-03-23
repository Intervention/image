<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawPolygonModifier extends AbstractDrawModifier
{
    public function __construct(public Polygon $drawable)
    {
    }

    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
