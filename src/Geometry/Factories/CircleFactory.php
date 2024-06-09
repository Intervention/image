<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Closure;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class CircleFactory implements DrawableFactoryInterface
{
    protected Circle $circle;

    /**
     * Create new factory instance
     *
     * @param PointInterface $pivot
     * @param null|Closure|Circle $init
     * @return void
     */
    public function __construct(
        protected PointInterface $pivot = new Point(),
        null|Closure|Circle $init = null,
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
     * @see DrawableFactoryInterface::init()
     */
    public static function init(null|Closure|DrawableInterface $init = null): self
    {
        return new self(init: $init);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public function create(): DrawableInterface
    {
        return $this->circle;
    }

    /**
     * Set the radius of the circle to be produced
     *
     * @param int $radius
     * @return CircleFactory
     */
    public function radius(int $radius): self
    {
        $this->circle->setSize($radius * 2, $radius * 2);

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
        $this->circle->setSize($diameter, $diameter);

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
     * Set the border color & border size of the ellipse to be produced
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
