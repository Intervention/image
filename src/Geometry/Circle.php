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

    public function diameter(int $diameter): self
    {
        return $this->setDiameter($diameter);
    }

    public function setDiameter(int $diameter): self
    {
        $this->setWidth($diameter);
        $this->setHeight($diameter);

        return $this;
    }

    public function getDiameter(): int
    {
        return $this->diameter;
    }

    public function radius(int $radius): self
    {
        return $this->setRadius($radius);
    }

    public function setRadius(int $radius): self
    {
        return $this->diameter(intval($radius * 2));
    }

    public function getRadius(): int
    {
        return intval($this->diameter / 2);
    }
}
