<?php

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;

class PolygonFactory
{
    protected Polygon $polygon;

    public function __construct(callable|Polygon $init)
    {
        $this->polygon = is_a($init, Polygon::class) ? $init : new Polygon([]);

        if (is_callable($init)) {
            $init($this);
        }
    }

    public function point(int $x, int $y): self
    {
        $this->polygon->addPoint(new Point($x, $y));

        return $this;
    }

    public function background(mixed $color): self
    {
        $this->polygon->setBackgroundColor($color);

        return $this;
    }

    public function border(mixed $color, int $size = 1): self
    {
        $this->polygon->setBorder($color, $size);

        return $this;
    }

    public function __invoke(): Polygon
    {
        return $this->polygon;
    }
}
