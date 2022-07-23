<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableInterface;

class Ellipse implements DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    public function __construct(
        protected int $width,
        protected int $height,
        protected ?Point $pivot = null
    ) {
        $this->pivot = $pivot ? $pivot : new Point();
    }

    public function size(int $width, int $height): self
    {
        return $this->setSize($width, $height);
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

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
