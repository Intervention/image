<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class Line implements DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    /**
     * Create new line instance
     *
     * @param Point $start
     * @param Point $end
     * @param int $width
     * @return void
     */
    public function __construct(
        protected Point $start,
        protected Point $end,
        protected int $width = 1
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::position()
     */
    public function position(): PointInterface
    {
        return $this->start;
    }

    /**
     * Return line width
     *
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Set line width
     *
     * @param int $width
     * @return Line
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get starting point of line
     *
     * @return Point
     */
    public function start(): Point
    {
        return $this->start;
    }

    /**
     * get end point of line
     *
     * @return Point
     */
    public function end(): Point
    {
        return $this->end;
    }

    /**
     * Set starting point of line
     *
     * @param Point $start
     * @return Line
     */
    public function setStart(Point $start): self
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Set starting point of line by coordinates
     *
     * @param int $x
     * @param int $y
     * @return Line
     */
    public function from(int $x, int $y): self
    {
        $this->start()->setX($x);
        $this->start()->setY($y);

        return $this;
    }

    /**
     * Set end point of line by coordinates
     *
     * @param int $x
     * @param int $y
     * @return Line
     */
    public function to(int $x, int $y): self
    {
        $this->end()->setX($x);
        $this->end()->setY($y);

        return $this;
    }

    /**
     * Set end point of line
     *
     * @param Point $end
     * @return Line
     */
    public function setEnd(Point $end): self
    {
        $this->end = $end;

        return $this;
    }
}
