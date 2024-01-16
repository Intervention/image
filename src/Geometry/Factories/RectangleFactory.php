<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;

class RectangleFactory
{
    protected Rectangle $rectangle;

    /**
     * Create new instance
     *
     * @param Point $pivot
     * @param callable|Rectangle $init
     * @return void
     */
    public function __construct(protected Point $pivot, callable|Rectangle $init)
    {
        $this->rectangle = is_a($init, Rectangle::class) ? $init : new Rectangle(0, 0, $pivot);

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * Set the size of the rectangle to be produced
     *
     * @param int $width
     * @param int $height
     * @return RectangleFactory
     */
    public function size(int $width, int $height): self
    {
        $this->rectangle->setSize($width, $height);

        return $this;
    }

    /**
     * Set the width of the rectangle to be produced
     *
     * @param int $width
     * @return RectangleFactory
     */
    public function width(int $width): self
    {
        $this->rectangle->setWidth($width);

        return $this;
    }

    /**
     * Set the height of the rectangle to be produced
     *
     * @param int $height
     * @return RectangleFactory
     */
    public function height(int $height): self
    {
        $this->rectangle->setHeight($height);

        return $this;
    }

    /**
     * Set the background color of the rectangle to be produced
     *
     * @param mixed $color
     * @return RectangleFactory
     */
    public function background(mixed $color): self
    {
        $this->rectangle->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the rectangle to be produced
     *
     * @param mixed $color
     * @param int $size
     * @return RectangleFactory
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->rectangle->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the rectangle
     *
     * @return Rectangle
     */
    public function __invoke(): Rectangle
    {
        return $this->rectangle;
    }
}
