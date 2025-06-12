<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Closure;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class EllipseFactory implements DrawableFactoryInterface
{
    protected Ellipse $ellipse;

    /**
     * Create new factory instance
     *
     * @return void
     */
    public function __construct(
        protected PointInterface $pivot = new Point(),
        null|Closure|Ellipse $init = null,
    ) {
        $this->ellipse = is_a($init, Ellipse::class) ? $init : new Ellipse(0, 0);
        $this->ellipse->setPosition($pivot);

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
        return $this->ellipse;
    }

    /**
     * Set the size of the ellipse to be produced
     */
    public function size(int $width, int $height): self
    {
        $this->ellipse->setSize($width, $height);

        return $this;
    }

    /**
     * Set the width of the ellipse to be produced
     */
    public function width(int $width): self
    {
        $this->ellipse->setWidth($width);

        return $this;
    }

    /**
     * Set the height of the ellipse to be produced
     */
    public function height(int $height): self
    {
        $this->ellipse->setHeight($height);

        return $this;
    }

    /**
     * Set the background color of the ellipse to be produced
     */
    public function background(mixed $color): self
    {
        $this->ellipse->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the ellipse to be produced
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->ellipse->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the ellipse
     */
    public function __invoke(): Ellipse
    {
        return $this->ellipse;
    }
}
