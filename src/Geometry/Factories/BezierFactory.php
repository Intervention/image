<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Closure;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class BezierFactory implements DrawableFactoryInterface
{
    protected Bezier $bezier;

    /**
     * Create new factory instance.
     */
    public function __construct(null|Closure|DrawableInterface $init = null)
    {
        $this->bezier = is_a($init, Bezier::class) ? $init : new Bezier([]);

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public static function create(null|Closure|DrawableInterface $init = null): self
    {
        return new self($init);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(?callable $init = null): Bezier
    {
        return (new self($init))->drawable();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::drawable()
     */
    public function drawable(): DrawableInterface
    {
        return $this->bezier;
    }

    /**
     * Add a point to the bezier to be produced.
     */
    public function point(int $x, int $y): self
    {
        $this->bezier->addPoint(new Point($x, $y));

        return $this;
    }

    /**
     * Set the background color of the bezier to be produced.
     */
    public function background(mixed $color): self
    {
        $this->bezier->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the bezier to be produced.
     *
     * @throws InvalidArgumentException
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->bezier->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the bezier.
     */
    public function __invoke(): Bezier
    {
        return $this->bezier;
    }
}
