<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawRectangleModifier extends AbstractDrawModifier
{
    /**
     * Create new modifier object
     */
    public function __construct(public Rectangle $drawable)
    {
        //
    }

    /**
     * Return object to be drawn
     */
    protected function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
