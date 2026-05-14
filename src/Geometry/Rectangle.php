<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class Rectangle extends Polygon implements DrawableInterface
{
    /**
     * Create new rectangle.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        int $width,
        int $height,
        protected PointInterface $pivot = new Point()
    ) {
        if ($width < 0) {
            throw new InvalidArgumentException(
                'Width of ' . $this::class . ' must be greater than or equal to 0'
            );
        }

        if ($height < 0) {
            throw new InvalidArgumentException(
                'Height of ' . $this::class . ' must be greater than or equal to 0'
            );
        }

        parent::__construct([
            new Point($this->pivot->x(), $this->pivot->y()),
            new Point($this->pivot->x() + $width, $this->pivot->y()),
            new Point($this->pivot->x() + $width, $this->pivot->y() - $height),
            new Point($this->pivot->x(), $this->pivot->y() - $height),
        ], $pivot);
    }

    /**
     * Calculate width of rectangle.
     */
    public function width(): int
    {
        return abs($this->mostLeftPoint()->x() - $this->mostRightPoint()->x());
    }

    /**
     * Calculate height of rectangle.
     */
    public function height(): int
    {
        return abs($this->mostBottomPoint()->y() - $this->mostTopPoint()->y());
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::setWidth()
     */
    public function setWidth(int $width): self
    {
        $this[1]->setX($this[0]->x() + $width);
        $this[2]->setX($this[3]->x() + $width);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::setHeight()
     */
    public function setHeight(int $height): self
    {
        $this[2]->setY($this[1]->y() + $height);
        $this[3]->setY($this[0]->y() + $height);

        return $this;
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
