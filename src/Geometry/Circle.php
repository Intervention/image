<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\PointInterface;

class Circle extends Ellipse
{
    /**
     * Create new Circle instance
     *
     * @param int $diameter
     * @param PointInterface $pivot
     * @return void
     */
    public function __construct(
        protected int $diameter,
        protected PointInterface $pivot = new Point()
    ) {
        $this->setWidth($diameter);
        $this->setHeight($diameter);
    }

    /**
     * Set diameter of circle
     *
     * @param int $diameter
     * @return Circle
     */
    public function setDiameter(int $diameter): self
    {
        $this->setWidth($diameter);
        $this->setHeight($diameter);

        return $this;
    }

    /**
     * Get diameter of circle
     *
     * @return int
     */
    public function diameter(): int
    {
        return $this->diameter;
    }

    /**
     * Set radius of circle
     *
     * @param int $radius
     * @return Circle
     */
    public function setRadius(int $radius): self
    {
        return $this->setDiameter(intval($radius * 2));
    }

    /**
     * Get radius of circle
     *
     * @return int
     */
    public function radius(): int
    {
        return intval($this->diameter / 2);
    }
}
