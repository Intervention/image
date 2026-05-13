<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class Rectangle implements DrawableInterface
{
    use HasBackgroundColor;
    use HasBorder;

    /**
     * Create new rectangle.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected int $width,
        protected int $height,
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
    }

    /**
     * Get width of rectangle.
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Set width of rectangle.
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get height of rectangle.
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Set height of rectangle.
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

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
