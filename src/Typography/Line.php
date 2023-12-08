<?php

namespace Intervention\Image\Typography;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\PointInterface;

class Line
{
    public function __construct(
        protected string $text,
        protected PointInterface $position = new Point()
    ) {
    }

    public function position(): PointInterface
    {
        return $this->position;
    }

    public function setPosition(Point $point): self
    {
        $this->position = $point;

        return $this;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
