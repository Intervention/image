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
 * @implements IteratorAggregate<PointInterface>
 * @implements ArrayAccess<int, PointInterface>
 */
class Bezier implements IteratorAggregate, Countable, ArrayAccess, DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    /**
     * Create new bezier instance
     *
     * @param array<PointInterface> $points
     * @return void
     */
    public function __construct(
        protected array $points = [],
        protected PointInterface $pivot = new Point()
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
        return $this->pivot;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setPosition()
     */
    public function setPosition(PointInterface $position): DrawableInterface
    {
        $this->pivot = $position;

        return $this;
    }

    /**
     * Implement iteration through all points of bezier
     *
     * @return Traversable<PointInterface>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->points);
    }

    /**
     * Return current pivot point
     */
    public function pivot(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * Change pivot point to given point
     */
    public function setPivot(PointInterface $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

    /**
     * Return first control point of bezier
     */
    public function first(): ?PointInterface
    {
        if ($point = reset($this->points)) {
            return $point;
        }

        return null;
    }

    /**
     * Return second control point of bezier
     */
    public function second(): ?PointInterface
    {
        if (array_key_exists(1, $this->points)) {
            return $this->points[1];
        }

        return null;
    }

    /**
     * Return third control point of bezier
     */
    public function third(): ?PointInterface
    {
        if (array_key_exists(2, $this->points)) {
            return $this->points[2];
        }

        return null;
    }

    /**
     * Return last control point of bezier
     */
    public function last(): ?PointInterface
    {
        if ($point = end($this->points)) {
            return $point;
        }

        return null;
    }

    /**
     * Return bezier's point count
     */
    public function count(): int
    {
        return count($this->points);
    }

    /**
     * Determine if point exists at given offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->points);
    }

    /**
     * Return point at given offset
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->points[$offset];
    }

    /**
     * Set point at given offset
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->points[$offset] = $value;
    }

    /**
     * Unset offset at given offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->points[$offset]);
    }

    /**
     * Add given point to bezier
     */
    public function addPoint(PointInterface $point): self
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
