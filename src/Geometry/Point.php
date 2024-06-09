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
     * {@inheritdoc}
     *
     * @see PointInterface::setX()
     */
    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PointInterface::x()
     */
    public function x(): int
    {
        return $this->x;
    }

    /**
     * {@inheritdoc}
     *
     * @see PointInterface::setY()
     */
    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PointInterface::y()
     */
    public function y(): int
    {
        return $this->y;
    }

    /**
     * {@inheritdoc}
     *
     * @see PointInterface::moveX()
     */
    public function moveX(int $value): self
    {
        $this->x += $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PointInterface::moveY()
     */
    public function moveY(int $value): self
    {
        $this->y += $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PointInterface::move()
     */
    public function move(int $x, int $y): self
    {
        return $this->moveX($x)->moveY($y);
    }

    /**
     * {@inheritdoc}
     *
     * @see PointInterface::setPosition()
     */
    public function setPosition(int $x, int $y): self
    {
        $this->setX($x);
        $this->setY($y);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PointInterface::rotate()
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
