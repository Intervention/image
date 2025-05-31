<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawBezierModifier extends AbstractDrawModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(public Bezier $drawable)
    {
        //
    }

    /**
     * Return object to be drawn
     */
    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
