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
class Polygon implements IteratorAggregate, Countable, ArrayAccess, DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    /**
     * Create new polygon instance
     *
     * @param array<PointInterface> $points
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
     * {@inheritdoc}
     *
     * @see DrawableInterface::setPosition()
     */
    public function setPosition(PointInterface $position): self
    {
        $this->pivot = $position;

        return $this;
    }

    /**
     * Implement iteration through all points of polygon
     *
     * @return Traversable<PointInterface>
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
     * @param PointInterface $pivot
     * @return Polygon
     */
    public function setPivot(PointInterface $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

    /**
     * Return first point of polygon
     *
     * @return ?PointInterface
     */
    public function first(): ?PointInterface
    {
        if ($point = reset($this->points)) {
            return $point;
        }

        return null;
    }

    /**
     * Return last point of polygon
     *
     * @return ?PointInterface
     */
    public function last(): ?PointInterface
    {
        if ($point = end($this->points)) {
            return $point;
        }

        return null;
    }

    /**
     * Return polygon's point count
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
     * @return PointInterface
     */
    public function offsetGet($offset): mixed
    {
        return $this->points[$offset];
    }

    /**
     * Set point at given offset
     *
     * @param mixed $offset
     * @param PointInterface $value
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
     * Add given point to polygon
     *
     * @param PointInterface $point
     * @return Polygon
     */
    public function addPoint(PointInterface $point): self
    {
        $this->points[] = $point;

        return $this;
    }

    /**
     * Calculate total horizontal span of polygon
     *
     * @return int
     */
    public function width(): int
    {
        return abs($this->mostLeftPoint()->x() - $this->mostRightPoint()->x());
    }

    /**
     * Calculate total vertical span of polygon
     *
     * @return int
     */
    public function height(): int
    {
        return abs($this->mostBottomPoint()->y() - $this->mostTopPoint()->y());
    }

    /**
     * Return most left point of all points in polygon
     *
     * @return PointInterface
     */
    public function mostLeftPoint(): PointInterface
    {
        $points = $this->points;

        usort($points, function (PointInterface $a, PointInterface $b): int {
            if ($a->x() === $b->x()) {
                return 0;
            }
            return $a->x() < $b->x() ? -1 : 1;
        });

        return $points[0];
    }

    /**
     * Return most right point in polygon
     *
     * @return PointInterface
     */
    public function mostRightPoint(): PointInterface
    {
        $points = $this->points;

        usort($points, function (PointInterface $a, PointInterface $b): int {
            if ($a->x() === $b->x()) {
                return 0;
            }
            return $a->x() > $b->x() ? -1 : 1;
        });

        return $points[0];
    }

    /**
     * Return most top point in polygon
     *
     * @return PointInterface
     */
    public function mostTopPoint(): PointInterface
    {
        $points = $this->points;

        usort($points, function (PointInterface $a, PointInterface $b): int {
            if ($a->y() === $b->y()) {
                return 0;
            }
            return $a->y() > $b->y() ? -1 : 1;
        });

        return $points[0];
    }

    /**
     * Return most bottom point in polygon
     *
     * @return PointInterface
     */
    public function mostBottomPoint(): PointInterface
    {
        $points = $this->points;

        usort($points, function (PointInterface $a, PointInterface $b): int {
            if ($a->y() === $b->y()) {
                return 0;
            }
            return $a->y() < $b->y() ? -1 : 1;
        });

        return $points[0];
    }

    /**
     * Return point in absolute center of the polygon
     *
     * @return PointInterface
     */
    public function centerPoint(): PointInterface
    {
        return new Point(
            $this->mostRightPoint()->x() - (intval(round($this->width() / 2))),
            $this->mostTopPoint()->y() - (intval(round($this->height() / 2)))
        );
    }

    /**
     * Align all points of polygon horizontally to given position around pivot point
     *
     * @param string $position
     * @return Polygon
     */
    public function align(string $position): self
    {
        switch (strtolower($position)) {
            case 'center':
            case 'middle':
                $diff = $this->centerPoint()->x() - $this->pivot()->x();
                break;

            case 'right':
                $diff = $this->mostRightPoint()->x() - $this->pivot()->x();
                break;

            default:
            case 'left':
                $diff = $this->mostLeftPoint()->x() - $this->pivot()->x();
                break;
        }

        foreach ($this->points as $point) {
            $point->setX(
                intval($point->x() - $diff)
            );
        }

        return $this;
    }

    /**
     * Align all points of polygon vertically to given position around pivot point
     *
     * @param string $position
     * @return Polygon
     */
    public function valign(string $position): self
    {
        switch (strtolower($position)) {
            case 'center':
            case 'middle':
                $diff = $this->centerPoint()->y() - $this->pivot()->y();
                break;

            case 'top':
                $diff = $this->mostTopPoint()->y() - $this->pivot()->y() - $this->height();
                break;

            default:
            case 'bottom':
                $diff = $this->mostBottomPoint()->y() - $this->pivot()->y() + $this->height();
                break;
        }

        foreach ($this->points as $point) {
            $point->setY(
                intval($point->y() - $diff),
            );
        }

        return $this;
    }

    /**
     * Rotate points of polygon around pivot point with given angle
     *
     * @param float $angle
     * @return Polygon
     */
    public function rotate(float $angle): self
    {
        $sin = sin(deg2rad($angle));
        $cos = cos(deg2rad($angle));

        foreach ($this->points as $point) {
            // translate point to pivot
            $point->setX(
                intval($point->x() - $this->pivot()->x()),
            );
            $point->setY(
                intval($point->y() - $this->pivot()->y()),
            );

            // rotate point
            $x = $point->x() * $cos - $point->y() * $sin;
            $y = $point->x() * $sin + $point->y() * $cos;

            // translate point back
            $point->setX(
                intval($x + $this->pivot()->x()),
            );
            $point->setY(
                intval($y + $this->pivot()->y()),
            );
        }

        return $this;
    }

    /**
     * Move all points by given amount on the x-axis
     *
     * @param int $amount
     * @return Polygon
     */
    public function movePointsX(int $amount): self
    {
        foreach ($this->points as $point) {
            $point->moveX($amount);
        }

        return $this;
    }

    /**
     * Move all points by given amount on the y-axis
     *
     * @param int $amount
     * @return Polygon
     */
    public function movePointsY(int $amount): self
    {
        foreach ($this->points as $point) {
            $point->moveY($amount);
        }

        return $this;
    }

    /**
     * Return array of all x/y values of all points of polygon
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
