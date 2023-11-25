<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class Line implements DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    public function __construct(
        protected Point $start,
        protected Point $end,
        protected int $width = 1
    ) {
    }

    public function position(): PointInterface
    {
        return $this->start;
    }

    public function width(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function start(): Point
    {
        return $this->start;
    }

    public function end(): Point
    {
        return $this->end;
    }

    public function setStart(Point $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function from(int $x, int $y): self
    {
        $this->start()->setX($x);
        $this->start()->setY($y);

        return $this;
    }

    public function to(int $x, int $y): self
    {
        $this->end()->setX($x);
        $this->end()->setY($y);

        return $this;
    }

    public function setEnd(Point $end): self
    {
        $this->end = $end;

        return $this;
    }
}
