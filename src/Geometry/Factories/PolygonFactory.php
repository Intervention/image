<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class PolygonFactory implements DrawableFactoryInterface
{
    protected Polygon $polygon;

    /**
     * Create new factory instance.
     */
    public function __construct(null|callable|Polygon $polygon = null)
    {
        $this->polygon = is_a($polygon, Polygon::class) ? clone $polygon : new Polygon([]);

        if (is_callable($polygon)) {
            $polygon($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(
        null|callable|DrawableInterface $drawable = null,
        ?callable $adjustments = null,
    ): Polygon {
        $factory = new self($drawable);

        if (is_callable($adjustments)) {
            $adjustments($factory);
        }

        return $factory->drawable();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::drawable()
     */
    public function drawable(): Polygon
    {
        return $this->polygon;
    }

    /**
     * Add a point to the polygon to be produced.
     */
    public function point(int $x, int $y): self
    {
        $this->polygon->addPoint(new Point($x, $y));

        return $this;
    }

    /**
     * Set the background color of the polygon to be produced.
     */
    public function background(string|ColorInterface $color): self
    {
        $this->polygon->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the polygon to be produced.
     *
     * @throws InvalidArgumentException
     */
    public function border(string|ColorInterface $color, int $size = 1): self
    {
        $this->polygon->setBorder($color, $size);

        return $this;
    }
}
