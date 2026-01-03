<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class EllipseFactory implements DrawableFactoryInterface
{
    protected Ellipse $ellipse;

    /**
     * Create new factory instance.
     */
    public function __construct(
        protected PointInterface $pivot = new Point(),
        null|callable|Ellipse $ellipse = null,
    ) {
        $this->ellipse = is_a($ellipse, Ellipse::class) ? $ellipse : new Ellipse(0, 0);
        $this->ellipse->setPosition($pivot);

        if (is_callable($ellipse)) {
            $ellipse($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public static function create(null|callable|DrawableInterface $drawable = null): self
    {
        return new self(ellipse: $drawable);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(null|callable|DrawableInterface $drawable = null): Ellipse
    {
        return (new self(ellipse: $drawable))->drawable();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::drawable()
     */
    public function drawable(): Ellipse
    {
        return $this->ellipse;
    }

    /**
     * Set the size of the ellipse to be produced.
     */
    public function size(int $width, int $height): self
    {
        $this->ellipse->setSize($width, $height);

        return $this;
    }

    /**
     * Set the width of the ellipse to be produced.
     */
    public function width(int $width): self
    {
        $this->ellipse->setWidth($width);

        return $this;
    }

    /**
     * Set the height of the ellipse to be produced.
     */
    public function height(int $height): self
    {
        $this->ellipse->setHeight($height);

        return $this;
    }

    /**
     * Set the background color of the ellipse to be produced.
     */
    public function background(mixed $color): self
    {
        $this->ellipse->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the ellipse to be produced.
     *
     * @throws InvalidArgumentException
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->ellipse->setBorder($color, $size);

        return $this;
    }
}
