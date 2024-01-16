<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;

class EllipseFactory
{
    protected Ellipse $ellipse;

    /**
     * Create new factory instance
     *
     * @param Point $pivot
     * @param callable|Ellipse $init
     * @return void
     */
    public function __construct(protected Point $pivot, callable|Ellipse $init)
    {
        $this->ellipse = is_a($init, Ellipse::class) ? $init : new Ellipse(0, 0, $pivot);

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * Set the size of the ellipse to be produced
     *
     * @param int $width
     * @param int $height
     * @return EllipseFactory
     */
    public function size(int $width, int $height): self
    {
        $this->ellipse->setSize($width, $height);

        return $this;
    }

    /**
     * Set the width of the ellipse to be produced
     *
     * @param int $width
     * @return EllipseFactory
     */
    public function width(int $width): self
    {
        $this->ellipse->setWidth($width);

        return $this;
    }

    /**
     * Set the height of the ellipse to be produced
     *
     * @param int $height
     * @return EllipseFactory
     */
    public function height(int $height): self
    {
        $this->ellipse->setHeight($height);

        return $this;
    }

    /**
     * Set the background color of the ellipse to be produced
     *
     * @param mixed $color
     * @return EllipseFactory
     */
    public function background(mixed $color): self
    {
        $this->ellipse->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the ellipse to be produced
     *
     * @param mixed $color
     * @param int $size
     * @return EllipseFactory
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->ellipse->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the ellipse
     *
     * @return Ellipse
     */
    public function __invoke(): Ellipse
    {
        return $this->ellipse;
    }
}
