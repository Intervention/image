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
        //
    }

    public function withBackground(ColorInterface $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function background(): ColorInterface
    {
        return $this->background;
    }
}
