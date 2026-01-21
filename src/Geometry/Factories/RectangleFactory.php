<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class RectangleFactory implements DrawableFactoryInterface
{
    protected Rectangle $rectangle;

    /**
     * Create new instance.
     */
    public function __construct(
        protected PointInterface $pivot = new Point(),
        null|callable|Rectangle $rectangle = null,
    ) {
        $this->rectangle = is_a($rectangle, Rectangle::class) ? $rectangle : new Rectangle(0, 0, $pivot);
        $this->rectangle->setPosition($pivot);

        if (is_callable($rectangle)) {
            $rectangle($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     *
     * @throws InvalidArgumentException
     */
    public static function create(null|callable|DrawableInterface $drawable = null): self
    {
        return new self(rectangle: $drawable);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(null|callable|DrawableInterface $drawable = null): Rectangle
    {
        return (new self(rectangle: $drawable))->drawable();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::drawable()
     */
    public function drawable(): Rectangle
    {
        return $this->rectangle;
    }

    /**
     * Set the size of the rectangle to be produced.
     */
    public function size(int $width, int $height): self
    {
        $this->rectangle->setSize($width, $height);

        return $this;
    }

    /**
     * Set the width of the rectangle to be produced.
     */
    public function width(int $width): self
    {
        $this->rectangle->setWidth($width);

        return $this;
    }

    /**
     * Set the height of the rectangle to be produced.
     */
    public function height(int $height): self
    {
        $this->rectangle->setHeight($height);

        return $this;
    }

    /**
     * Set the background color of the rectangle to be produced.
     */
    public function background(string|ColorInterface $color): self
    {
        $this->rectangle->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the rectangle to be produced.
     *
     * @throws InvalidArgumentException
     */
    public function border(string|ColorInterface $color, int $size = 1): self
    {
        $this->rectangle->setBorder($color, $size);

        return $this;
    }
}
