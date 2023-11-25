<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class Ellipse implements DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    public function __construct(
        protected int $width,
        protected int $height,
        protected PointInterface $pivot = new Point()
    ) {
    }

    public function position(): PointInterface
    {
        return $this->pivot;
    }

    public function pivot(): PointInterface
    {
        return $this->pivot;
    }

    public function setSize(int $width, int $height): self
    {
        return $this->setWidth($width)->setHeight($height);
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function width(): int
    {
        return $this->width;
    }

    public function height(): int
    {
        return $this->height;
    }
}
