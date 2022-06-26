<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Size implements SizeInterface
{
    public function __construct(
        protected int $width,
        protected int $height,
        protected ?Point $pivot = null
    ) {
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

    public function addWidth(int $value): SizeInterface
    {
        $this->width = $this->width + $value;

        return $this;
    }

    public function subWidth(int $value): SizeInterface
    {
        $this->width = $this->width - $value;

        return $this;
    }

    public function addHeight(int $value): SizeInterface
    {
        $this->height = $this->height + $value;

        return $this;
    }

    public function subHeight(int $value): SizeInterface
    {
        $this->height = $this->height - $value;

        return $this;
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

    public function setPivot(PointInterface $pivot): SizeInterface
    {
        $this->pivot = $pivot;

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

    /**
     * Aligns current size's pivot point to given position
     * and moves point automatically by offset.
     *
     * @param  string  $position
     * @param  int     $offset_x
     * @param  int     $offset_y
     * @return Size
     */
    public function alignPivot(string $position, int $offset_x = 0, int $offset_y = 0): SizeInterface
    {
        switch (strtolower($position)) {
            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $x = intval($this->width / 2);
                $y = 0 + $offset_y;
                break;

            case 'top-right':
            case 'right-top':
                $x = $this->width - $offset_x;
                $y = 0 + $offset_y;
                break;

            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $x = 0 + $offset_x;
                $y = intval($this->height / 2);
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $x = $this->width - $offset_x;
                $y = intval($this->height / 2);
                break;

            case 'bottom-left':
            case 'left-bottom':
                $x = 0 + $offset_x;
                $y = $this->height - $offset_y;
                break;

            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $x = intval($this->width / 2);
                $y = $this->height - $offset_y;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $x = $this->width - $offset_x;
                $y = $this->height - $offset_y;
                break;

            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $x = intval($this->width / 2) + $offset_x;
                $y = intval($this->height / 2) + $offset_y;
                break;

            default:
            case 'top-left':
            case 'left-top':
                $x = 0 + $offset_x;
                $y = 0 + $offset_y;
                break;
        }

        $this->pivot->setPosition($x, $y);

        return $this;
    }

    public function alignPivotTo(SizeInterface $size, string $position): SizeInterface
    {
        $reference = new Size($size->getWidth(), $size->getHeight());
        $reference->alignPivot($position);

        $this->alignPivot($position)->setPivot(
            $reference->getRelativePositionTo($this)
        );

        return $this;
    }

    /**
     * Calculate the relative position to another Size
     * based on the pivot point settings of both sizes.
     *
     * @param  Size   $size
     * @return Point
     */
    public function getRelativePositionTo(SizeInterface $size): PointInterface
    {
        $x = $this->getPivot()->getX() - $size->getPivot()->getX();
        $y = $this->getPivot()->getY() - $size->getPivot()->getY();

        return new Point($x, $y);
    }

    public function toPolygon(): Polygon
    {
        $polygon = new Polygon([
            $this->pivot // top/left
        ], $this->pivot);

        // top/right
        $polygon->addPoint(
            new Point($this->pivot->getX() + $this->getWidth(), $this->pivot->getY())
        );

        // bottom/right
        $polygon->addPoint(
            new Point($this->pivot->getX() + $this->getWidth(), $this->pivot->getY() - $this->getHeight())
        );

        // bottom/left
        $polygon->addPoint(
            new Point($this->pivot->getX(), $this->pivot->getY() - $this->getHeight())
        );

        return $polygon;
    }

    protected function getResizer(?int $width = null, ?int $height = null): Resizer
    {
        return new Resizer($width, $height);
    }

    public function resize(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->getResizer($width, $height)->resize($this);
    }

    public function resizeDown(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->getResizer($width, $height)->resizeDown($this);
    }

    public function scale(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->getResizer($width, $height)->scale($this);
    }

    public function scaleDown(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->getResizer($width, $height)->scaleDown($this);
    }

    public function cover(int $width, int $height): SizeInterface
    {
        return $this->getResizer($width, $height)->cover($this);
    }

    public function contain(int $width, int $height): SizeInterface
    {
        return $this->getResizer($width, $height)->contain($this);
    }
}
