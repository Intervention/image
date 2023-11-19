<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\ColorInterface;

class Pixel extends Point
{
    public function __construct(
        protected ColorInterface $background,
        protected int $x,
        protected int $y
    ) {
    }

    public function setBackgroundColor(ColorInterface $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function backgroundColor(): ColorInterface
    {
        return $this->background;
    }
}
