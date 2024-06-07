<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class CircleFactory implements DrawableFactoryInterface
{
    protected Circle $circle;

    /**
     * Create new factory instance
     *
     * @param Point $pivot
     * @param null|callable|Circle $init
     * @return void
     */
    final public function __construct(
        protected Point $pivot = new Point(0, 0),
        null|callable|Circle $init = null,
    ) {
        $this->circle = is_a($init, Circle::class) ? $init : new Circle(0, $pivot);

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public static function create(null|callable|DrawableInterface $init = null): DrawableFactoryInterface
    {
        return new static(init: $init);
    }

    /**
     * Set position of ellipse
     *
     * @param int $x
     * @param int $y
     * @return CircleFactory
     */
    public function position(int $x, int $y): self
    {
        $this->circle->setPivot(new Point($x, $y));

        return $this;
    }

    /**
     * Set the radius of the circle to be produced
     *
     * @param int $radius
     * @return CircleFactory
     */
    public function radius(int $radius): self
    {
        $this->circle->setRadius($radius);

        return $this;
    }

    /**
     * Set the diameter of the circle to be produced
     *
     * @param int $diameter
     * @return CircleFactory
     */
    public function diameter(int $diameter): self
    {
        $this->circle->setDiameter($diameter);

        return $this;
    }

    /**
     * Set the background color of the circle to be produced
     *
     * @param mixed $color
     * @return CircleFactory
     */
    public function background(mixed $color): self
    {
        $this->circle->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the circle to be produced
     *
     * @param mixed $color
     * @param int $size
     * @return CircleFactory
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->circle->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the circle
     *
     * @return Circle
     */
    public function __invoke(): Circle
    {
        return $this->circle;
    }
}
