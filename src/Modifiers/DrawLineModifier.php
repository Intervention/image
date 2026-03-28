<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawLineModifier extends AbstractDrawModifier
{
    /**
     * Create new modifier object.
     */
    public function __construct(public Line $drawable)
    {
        //
    }

    /**
     * Return object to be drawn.
     */
    protected function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
