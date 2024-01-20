<?php

declare(strict_types=1);

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
        int $diameter,
        PointInterface $pivot = new Point()
    ) {
        parent::__construct($diameter, $diameter, $pivot);
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
        return $this->width();
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
        return intval(round($this->diameter() / 2));
    }
}
