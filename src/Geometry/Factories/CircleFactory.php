<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class CircleFactory implements DrawableFactoryInterface
{
    protected Circle $circle;

    /**
     * Create new factory instance.
     */
    public function __construct(
        protected PointInterface $pivot = new Point(),
        null|callable|DrawableInterface $init = null,
    ) {
        $this->circle = is_a($init, Circle::class) ? $init : new Circle(0);
        $this->circle->setPosition($pivot);

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public static function create(null|callable|DrawableInterface $init = null): self
    {
        return new self(init: $init);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(?callable $init = null): Circle
    {
        return (new self(init: $init))->drawable();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::drawable()
     */
    public function drawable(): Circle
    {
        return $this->circle;
    }

    /**
     * Set the radius of the circle to be produced.
     */
    public function radius(int $radius): self
    {
        $this->circle->setSize($radius * 2, $radius * 2);

        return $this;
    }

    /**
     * Set the diameter of the circle to be produced.
     */
    public function diameter(int $diameter): self
    {
        $this->circle->setSize($diameter, $diameter);

        return $this;
    }

    /**
     * Set the background color of the circle to be produced.
     */
    public function background(mixed $color): self
    {
        $this->circle->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the ellipse to be produced.
     *
     * @throws InvalidArgumentException
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->circle->setBorder($color, $size);

        return $this;
    }
}
