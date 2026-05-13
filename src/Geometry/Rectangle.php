<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use ArrayAccess;
use Countable;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;
use Traversable;

/**
 * @implements ArrayAccess<int, PointInterface>
 */
class Rectangle extends Size implements SizeInterface, DrawableInterface, ArrayAccess, Countable
{
    use HasBackgroundColor;
    use HasBorder;

    /**
     * @return array<int, PointInterface>
     */
    public function points(): array
    {
        $points = [];

        foreach (Polygon::fromSize($this) as $point) {
            $points[] = $point;
        }

        return $points;
    }

    /**
     * Return rectangle's point count.
     */
    public function count(): int
    {
        return count($this->points());
    }

    /**
     * Determine if point exists at given offset.
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->points());
    }

    /**
     * Return point at given offset.
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->points()[$offset];
    }

    /**
     * Set point at given offset
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->points()[$offset] = $value;
    }

    /**
     * Unset offset at given offset.
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->points()[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setPosition()
     */
    public function setPosition(PointInterface $position): self
    {
        parent::setPosition($position);

        return $this;
    }

    public function position(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Traversable
    {
        return Polygon::fromSize($this)->getIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::factory()
     *
     * @throws RuntimeException
     */
    public function factory(): DrawableFactoryInterface
    {
        // @phpstan-ignore missingType.checkedException
        return new RectangleFactory($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::adjust()
     *
     * @throws RuntimeException
     */
    public function adjust(callable $adjustments): DrawableInterface
    {
        $factory = $this->factory();
        $adjustments($factory);

        return $factory->drawable();
    }
}
