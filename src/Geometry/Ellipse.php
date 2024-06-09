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
     * @param int $width
     * @param int $height
     * @param PointInterface $pivot
     * @return void
     */
    public function __construct(
        protected int $width,
        protected int $height,
        protected PointInterface $pivot = new Point()
    ) {
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
     *
     * @return PointInterface
     */
    public function pivot(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * Set size of Ellipse
     *
     * @param int $width
     * @param int $height
     * @return Ellipse
     */
    public function setSize(int $width, int $height): self
    {
        return $this->setWidth($width)->setHeight($height);
    }

    /**
     * Set width of Ellipse
     *
     * @param int $width
     * @return Ellipse
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set height of Ellipse
     *
     * @param int $height
     * @return Ellipse
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get width of Ellipse
     *
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Get height of Ellipse
     *
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }
}
