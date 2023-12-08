<?php

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;

class RectangleFactory
{
    protected Rectangle $rectangle;

    public function __construct(protected Point $pivot, callable|Rectangle $init)
    {
        $this->rectangle = is_a($init, Rectangle::class) ? $init : new Rectangle(0, 0, $pivot);

        if (is_callable($init)) {
            $init($this);
        }
    }

    public function size(int $width, int $height): self
    {
        $this->rectangle->setSize($width, $height);

        return $this;
    }

    public function width(int $width): self
    {
        $this->rectangle->setWidth($width);

        return $this;
    }

    public function height(int $height): self
    {
        $this->rectangle->setHeight($height);

        return $this;
    }

    public function background(mixed $color): self
    {
        $this->rectangle->setBackgroundColor($color);

        return $this;
    }

    public function border(mixed $color, int $size = 1): self
    {
        $this->rectangle->setBorder($color, $size);

        return $this;
    }

    public function __invoke(): Rectangle
    {
        return $this->rectangle;
    }
}
