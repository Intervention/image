<?php

namespace Intervention\Image\Geometry;

class Circle extends Ellipse
{
    public function __construct(
        int $diameter,
        protected ?Point $pivot = null
    ) {
        $this->setWidth($diameter);
        $this->setHeight($diameter);
        $this->pivot = $pivot ? $pivot : new Point();
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
