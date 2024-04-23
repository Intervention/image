<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\PointInterface;

class Point implements PointInterface
{
    /**
     * Create new point instance
     *
     * @param int $x
     * @param int $y
     * @return void
     */
    public function __construct(
        protected int $x = 0,
        protected int $y = 0
    ) {
    }

    /**
     * Sets X coordinate
     *
     * @param int $x
     */
    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get X coordinate
     *
     * @return int
     */
    public function x(): int
    {
        return $this->x;
    }

    /**
     * Sets Y coordinate
     *
     * @param int $y
     */
    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get Y coordinate
     *
     * @return int
     */
    public function y(): int
    {
        return $this->y;
    }

    /**
     * Move X coordinate
     *
     * @param int $value
     */
    public function moveX(int $value): self
    {
        $this->x += $value;

        return $this;
    }

    /**
     * Move Y coordinate
     *
     * @param int $value
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
     * @param int $x
     * @param int $y
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
     * @param float $angle
     * @param PointInterface $pivot
     * @return Point
     */
    public function rotate(float $angle, PointInterface $pivot): self
    {
        $sin = round(sin(deg2rad($angle)), 6);
        $cos = round(cos(deg2rad($angle)), 6);

        return $this->setPosition(
            intval($cos * ($this->x() - $pivot->x()) - $sin * ($this->y() - $pivot->y()) + $pivot->x()),
            intval($sin * ($this->x() - $pivot->x()) + $cos * ($this->y() - $pivot->y()) + $pivot->y())
        );
    }
}
