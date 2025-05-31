<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Closure;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class PolygonFactory implements DrawableFactoryInterface
{
    protected Polygon $polygon;

    /**
     * Create new factory instance
     *
     * @return void
     */
    public function __construct(null|Closure|Polygon $init = null)
    {
        $this->polygon = is_a($init, Polygon::class) ? $init : new Polygon([]);

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
        return new self($init);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public function create(): DrawableInterface
    {
        return $this->polygon;
    }

    /**
     * Add a point to the polygon to be produced
     */
    public function point(int $x, int $y): self
    {
        $this->polygon->addPoint(new Point($x, $y));

        return $this;
    }

    /**
     * Set the background color of the polygon to be produced
     */
    public function background(mixed $color): self
    {
        $this->polygon->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the polygon to be produced
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->polygon->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the polygon
     */
    public function __invoke(): Polygon
    {
        return $this->polygon;
    }
}
