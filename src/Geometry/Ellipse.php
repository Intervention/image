<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class Ellipse implements DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

    /**
     * Create new Ellipse
     *
     * @return void
     */
    public function __construct(
        protected int $width,
        protected int $height,
        protected PointInterface $pivot = new Point()
    ) {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::position()
     */
    public function position(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setPosition()
     */
    public function setPosition(PointInterface $position): self
    {
        $this->pivot = $position;

        return $this;
    }

    /**
     * Return pivot point of Ellipse
     */
    public function pivot(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * Set size of Ellipse
     */
    public function setSize(int $width, int $height): self
    {
        return $this->setWidth($width)->setHeight($height);
    }

    /**
     * Set width of Ellipse
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set height of Ellipse
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get width of Ellipse
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Get height of Ellipse
     */
    public function height(): int
    {
        return $this->height;
    }
}
