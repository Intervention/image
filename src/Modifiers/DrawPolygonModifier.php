<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\DrawableInterface;

class DrawPolygonModifier extends AbstractDrawModifier
{
    public function __construct(public Polygon $drawable)
    {
        if ($drawable->count() < 3) {
            throw new InvalidArgumentException('The polygon must have at least 3 points');
        }
    }

    /**
     * Return object to be drawn.
     */
    protected function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
