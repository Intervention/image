<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawLineModifier extends AbstractDrawModifier
{
    public function __construct(public Line $drawable)
    {
    }

    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
