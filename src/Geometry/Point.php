<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\PointInterface;

class Point implements PointInterface
{
    public function __construct(
        protected int $x = 0,
        protected int $y = 0
    ) {
    }

    /**
     * Sets X coordinate
     *
     * @param integer $x
     */
    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get X coordinate
     *
     * @return integer
     */
    public function x(): int
    {
        return $this->x;
    }

    /**
     * Sets Y coordinate
     *
     * @param integer $y
     */
    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get Y coordinate
     *
     * @return integer
     */
    public function y(): int
    {
        return $this->y;
    }

    /**
     * Move X coordinate
     *
     * @param integer $value
     */
    public function moveX(int $value): self
    {
        $this->x += $value;

        return $this;
    }

    /**
     * Move Y coordinate
     *
     * @param integer $value
     */
    public function moveY(int $value): self
    {
        $this->y += $value;

        return $this;
    }

    public function move(int $x, int $y): self
    {
        return $this->moveX($x)->moveY($y);
    }

    /**
     * Sets both X and Y coordinate
     *
     * @param  integer $x
     * @param  integer $y
     * @return Point
     */
    public function setPosition(int $x, int $y): self
    {
        $this->setX($x);
        $this->setY($y);

        return $this;
    }

    /**
     * Rotate point ccw around pivot
     *
     * @param  float $angle
     * @param  Point $pivot
     * @return Point
     */
    public function rotate(float $angle, Point $pivot): self
    {
        $sin = round(sin(deg2rad($angle)), 6);
        $cos = round(cos(deg2rad($angle)), 6);

        return $this->setPosition(
            intval($cos * ($this->x - $pivot->x) - $sin * ($this->y - $pivot->y) + $pivot->x),
            intval($sin * ($this->x - $pivot->x) + $cos * ($this->y - $pivot->y) + $pivot->y)
        );
    }
}
