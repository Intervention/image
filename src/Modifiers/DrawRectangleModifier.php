<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawRectangleModifier extends AbstractDrawModifier
{
    public function __construct(public Rectangle $drawable)
    {
    }

    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
