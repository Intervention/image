<?php

namespace Intervention\Image\Geometry\Factories;

class CircleFactory extends EllipseFactory
{
    public function radius(int $radius): self
    {
        $this->ellipse->setSize($radius * 2, $radius * 2);

        return $this;
    }

    public function diameter(int $diameter): self
    {
        $this->ellipse->setSize($diameter, $diameter);

        return $this;
    }
}
