<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Traversable;
use IteratorAggregate;
use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

/**
 * @implements IteratorAggregate<Point>
 * @implements ArrayAccess<int, Point>
 */
class Bezier implements IteratorAggregate, Countable, ArrayAccess, DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    /**
     * Create new bezier instance
     *
     * @param array<Point> $points
     * @param PointInterface $pivot
     * @return void
     */
    public function __construct(
        protected array $points = [],
        protected PointInterface $pivot = new Point()
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::position()
     */
    public function position(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * Implement iteration through all points of bezier
     *
     * @return Traversable<Point>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->points);
    }

    /**
     * Return current pivot point
     *
     * @return PointInterface
     */
    public function pivot(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * Change pivot point to given point
     *
     * @param Point $pivot
     * @return Bezier
     */
    public function setPivot(Point $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

    /**
     * Return first control point of bezier
     *
     * @return ?Point
     */
    public function first(): ?Point
    {
        if ($point = reset($this->points)) {
            return $point;
        }

        return null;
    }

    /**
     * Return second control point of bezier
     *
     * @return ?Point
     */
    public function second(): ?Point
    {
        if (array_key_exists(1, $this->points)) {
            return $this->points[1];
        }

        return null;
    }

    /**
     * Return third control point of bezier
     *
     * @return ?Point
     */
    public function third(): ?Point
    {
        if (array_key_exists(2, $this->points)) {
            return $this->points[2];
        }

        return null;
    }

    /**
     * Return last control point of bezier
     *
     * @return ?Point
     */
    public function last(): ?Point
    {
        if ($point = end($this->points)) {
            return $point;
        }

        return null;
    }

    /**
     * Return bezier's point count
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->points);
    }

    /**
     * Determine if point exists at given offset
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->points);
    }

    /**
     * Return point at given offset
     *
     * @param mixed $offset
     * @return Point
     */
    public function offsetGet($offset): mixed
    {
        return $this->points[$offset];
    }

    /**
     * Set point at given offset
     *
     * @param mixed $offset
     * @param Point $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->points[$offset] = $value;
    }

    /**
     * Unset offset at given offset
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->points[$offset]);
    }

    /**
     * Add given point to bezier
     *
     * @param Point $point
     * @return Bezier
     */
    public function addPoint(Point $point): self
    {
        $this->points[] = $point;

        return $this;
    }

    /**
     * Return array of all x/y values of all points of bezier
     *
     * @return array<int>
     */
    public function toArray(): array
    {
        $coordinates = [];
        foreach ($this->points as $point) {
            $coordinates[] = $point->x();
            $coordinates[] = $point->y();
        }

        return $coordinates;
    }
}
