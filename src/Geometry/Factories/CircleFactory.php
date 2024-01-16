<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

class CircleFactory extends EllipseFactory
{
    /**
     * Set the radius of the circle to be produced
     *
     * @param int $radius
     * @return CircleFactory
     */
    public function radius(int $radius): self
    {
        $this->ellipse->setSize($radius * 2, $radius * 2);

        return $this;
    }

    /**
     * Set the diameter of the circle to be produced
     *
     * @param int $diameter
     * @return CircleFactory
     */
    public function diameter(int $diameter): self
    {
        $this->ellipse->setSize($diameter, $diameter);

        return $this;
    }
}
