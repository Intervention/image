<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Tools\Resizer;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Rectangle extends Polygon implements SizeInterface
{
    public function __construct(
        int $width,
        int $height,
        protected ?Point $pivot = null
    ) {
        $this->pivot = $pivot ? $pivot : new Point();
        $this->addPoint(new Point($this->pivot->getX(), $this->pivot->getY()));
        $this->addPoint(new Point($this->pivot->getX() + $width, $this->pivot->getY()));
        $this->addPoint(new Point($this->pivot->getX() + $width, $this->pivot->getY() - $height));
        $this->addPoint(new Point($this->pivot->getX(), $this->pivot->getY() - $height));
    }

    public function withWidth(int $width): self
    {
        $this[1]->setX($this[0]->getX() + $width);
        $this[2]->setX($this[3]->getX() + $width);

        return $this;
    }

    public function withHeight(int $height): self
    {
        $this[2]->setY($this[1]->getY() + $height);
        $this[3]->setY($this[0]->getY() + $height);

        return $this;
    }

    public function pivot(): Point
    {
        return $this->pivot;
    }

    public function withPivot(PointInterface $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

    /**
     * Aligns current size's pivot point to given position
     * and moves point automatically by offset.
     *
     * @param  string  $position
     * @param  int     $offset_x
     * @param  int     $offset_y
     * @return Rectangle
     */
    // TODO: rename method to movePivot
    public function alignPivot(string $position, int $offset_x = 0, int $offset_y = 0): self
    {
        switch (strtolower($position)) {
            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $x = intval($this->width() / 2);
                $y = 0 + $offset_y;
                break;

            case 'top-right':
            case 'right-top':
                $x = $this->width() - $offset_x;
                $y = 0 + $offset_y;
                break;

            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $x = 0 + $offset_x;
                $y = intval($this->height() / 2);
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $x = $this->width() - $offset_x;
                $y = intval($this->height() / 2);
                break;

            case 'bottom-left':
            case 'left-bottom':
                $x = 0 + $offset_x;
                $y = $this->height() - $offset_y;
                break;

            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $x = intval($this->width() / 2);
                $y = $this->height() - $offset_y;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $x = $this->width() - $offset_x;
                $y = $this->height() - $offset_y;
                break;

            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $x = intval($this->width() / 2) + $offset_x;
                $y = intval($this->height() / 2) + $offset_y;
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

    // TODO: rename to alignPivot
    public function alignPivotTo(SizeInterface $size, string $position): self
    {
        $reference = new self($size->width(), $size->height());
        $reference->alignPivot($position);

        $this->alignPivot($position)->withPivot(
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
    public function getRelativePositionTo(SizeInterface $rectangle): PointInterface
    {
        return new Point(
            $this->pivot()->getX() - $rectangle->pivot()->getX(),
            $this->pivot()->getY() - $rectangle->pivot()->getY()
        );
    }

    public function getAspectRatio(): float
    {
        return $this->width() / $this->height();
    }

    public function fitsInto(SizeInterface $size): bool
    {
        if ($this->width() > $size->width()) {
            return false;
        }

        if ($this->height() > $size->height()) {
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
        return $this->width() > $this->height();
    }

    /**
     * Determine if size is portrait format
     *
     * @return boolean
     */
    public function isPortrait(): bool
    {
        return $this->width() < $this->height();
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
