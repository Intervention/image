<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawEllipseModifier extends AbstractDrawModifier
{
    public function __construct(public Ellipse $drawable)
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
