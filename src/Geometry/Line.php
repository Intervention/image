<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Geometry\Factories\LineFactory;
use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class Line implements DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    /**
     * Create new line instance.
     */
    public function __construct(
        protected PointInterface $start,
        protected PointInterface $end,
        protected int $width = 1
    ) {
        //
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
     * {@inheritdoc}
     *
     * @see DrawableInterface::setPosition()
     */
    public function setPosition(PointInterface $position): DrawableInterface
    {
        $this->start = $position;

        return $this;
    }

    /**
     * Return line width
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Set line width.
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get starting point of line.
     */
    public function start(): PointInterface
    {
        return $this->start;
    }

    /**
     * get end point of line.
     */
    public function end(): PointInterface
    {
        return $this->end;
    }

    /**
     * Set starting point of line.
     */
    public function setStart(PointInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Set starting point of line by coordinates.
     */
    public function from(int $x, int $y): self
    {
        $this->start()->setX($x);
        $this->start()->setY($y);

        return $this;
    }

    /**
     * Set end point of line by coordinates.
     */
    public function to(int $x, int $y): self
    {
        $this->end()->setX($x);
        $this->end()->setY($y);

        return $this;
    }

    /**
     * Set end point of line.
     */
    public function setEnd(PointInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::factory()
     */
    public function factory(): DrawableFactoryInterface
    {
        return new LineFactory($this);
    }

    /**
     * Clone line.
     */
    public function __clone(): void
    {
        $this->start = clone $this->start;
        $this->end = clone $this->end;

        if ($this->backgroundColor instanceof AbstractColor) {
            $this->backgroundColor = clone $this->backgroundColor;
        }

        if ($this->borderColor instanceof AbstractColor) {
            $this->borderColor = clone $this->borderColor;
        }
    }
}
