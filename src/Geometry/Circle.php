<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\PointInterface;

class Circle extends Ellipse
{
    public function __construct(
        protected int $diameter,
        protected PointInterface $pivot = new Point()
    ) {
        $this->setWidth($diameter);
        $this->setHeight($diameter);
    }

    public function setDiameter(int $diameter): self
    {
        $this->setWidth($diameter);
        $this->setHeight($diameter);

        return $this;
    }

    public function diameter(): int
    {
        return $this->diameter;
    }

    public function setRadius(int $radius): self
    {
        return $this->setDiameter(intval($radius * 2));
    }

    public function radius(): int
    {
        return intval($this->diameter / 2);
    }
}
