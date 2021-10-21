<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Size implements SizeInterface
{
    protected $width;
    protected $height;
    protected $pivot;

    public function __construct(int $width, int $height, Point $pivot = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->pivot = $pivot ? $pivot : new Point();
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Get current pivot point
     *
     * @return Point
     */
    public function getPivot(): PointInterface
    {
        return $this->pivot;
    }

    public function setWidth(int $width): SizeInterface
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight(int $height): SizeInterface
    {
        $this->height = $height;

        return $this;
    }

    public function getAspectRatio(): float
    {
        return $this->width / $this->height;
    }

    public function fitsInto(SizeInterface $size): bool
    {
        if ($this->getWidth() > $size->getWidth()) {
            return false;
        }

        if ($this->getHeight() > $size->getHeight()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if size is landscape format
     *
     * @return boolean
     */
    public function isLandscape(): bool
    {
        return $this->getWidth() > $this->getHeight();
    }

    /**
     * Determine if size is portrait format
     *
     * @return boolean
     */
    public function isPortrait(): bool
    {
        return $this->getWidth() < $this->getHeight();
    }
}
