<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class BezierFactory implements DrawableFactoryInterface
{
    protected Bezier $bezier;

    /**
     * Create new factory instance.
     */
    public function __construct(null|callable|Bezier $bezier = null)
    {
        $this->bezier = is_a($bezier, Bezier::class) ? $bezier : new Bezier([]);

        if (is_callable($bezier)) {
            $bezier($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public static function create(null|callable|DrawableInterface $drawable = null): self
    {
        return new self($drawable);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(null|callable|DrawableInterface $drawable = null): Bezier
    {
        return (new self($drawable))->drawable();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::drawable()
     */
    public function drawable(): Bezier
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
    public function background(string|ColorInterface $color): self
    {
        $this->bezier->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the bezier to be produced.
     *
     * @throws InvalidArgumentException
     */
    public function border(string|ColorInterface $color, int $size = 1): self
    {
        $this->bezier->setBorder($color, $size);

        return $this;
    }
}
