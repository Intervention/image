<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableInterface;

class Line implements DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    public function __construct(
        protected Point $start,
        protected Point $end,
        protected int $width = 1
    ) {
        //
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function width(int $width): self
    {
        return $this->setWidth($width);
    }

    public function color($color): self
    {
        $this->setBackgroundColor($color);

        return $this;
    }

    public function getStart(): Point
    {
        return $this->start;
    }

    public function getEnd(): Point
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
        $this->getStart()->setX($x);
        $this->getStart()->setY($y);

        return $this;
    }

    public function to(int $x, int $y): self
    {
        $this->getEnd()->setX($x);
        $this->getEnd()->setY($y);

        return $this;
    }

    public function setEnd(Point $end): self
    {
        $this->end = $end;

        return $this;
    }
}
