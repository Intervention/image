<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class CircleFactory implements DrawableFactoryInterface
{
    protected Circle $circle;

    /**
     * Create new factory instance.
     */
    public function __construct(null|callable|Circle $circle = null)
    {
        $this->circle = is_a($circle, Circle::class) ? $circle : new Circle(0);

        if (is_callable($circle)) {
            $circle($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public static function create(null|callable|DrawableInterface $circle = null): self
    {
        return new self($circle);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(null|callable|DrawableInterface $circle = null): Circle // TODO: rename to create(), remove old create()
    {
        return (new self($circle))->drawable();
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
    public function background(string|ColorInterface $color): self
    {
        $this->circle->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the ellipse to be produced.
     *
     * @throws InvalidArgumentException
     */
    public function border(string|ColorInterface $color, int $size = 1): self
    {
        $this->circle->setBorder($color, $size);

        return $this;
    }

    public function at(int $x, int $y): self
    {
        $this->circle->position()->setPosition($x, $y);

        return $this;
    }
}
