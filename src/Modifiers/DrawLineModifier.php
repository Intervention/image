<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawLineModifier extends AbstractDrawModifier
{
    /**
     * Create new modifier object
     *
     * @param Line $drawable
     * @return void
     */
    public function __construct(public Line $drawable)
    {
    }

    /**
     * Return object to be drawn
     *
     * @return DrawableInterface
     */
    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
