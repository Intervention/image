<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Tools\RectangleResizer;
use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Rectangle extends Polygon implements SizeInterface, DrawableInterface
{
    use HasBorder;
    use HasBackgroundColor;

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

    /**
     * Set the rectangle dimensions to given width & height
     *
     * @param int $width
     * @param int $height
     * @return Rectangle
     */
    public function setSize(int $width, int $height): self
    {
        return $this->setWidth($width)->setHeight($height);
    }

    /**
     * Alias of self::setSize()
     */
    public function size(int $width, int $height): self
    {
        return $this->setSize($width, $height);
    }

    public function setWidth(int $width): self
    {
        $this[1]->setX($this[0]->getX() + $width);
        $this[2]->setX($this[3]->getX() + $width);

        return $this;
    }

    public function setHeight(int $height): self
    {
        $this[2]->setY($this[1]->getY() + $height);
        $this[3]->setY($this[0]->getY() + $height);

        return $this;
    }

    public function getPivot(): Point
    {
        return $this->pivot;
    }

    public function setPivot(PointInterface $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

    /**
     * Move current pivot of current rectangle to given position
     * and moves point automatically by offset.
     *
     * @param  string  $position
     * @param  int     $offset_x
     * @param  int     $offset_y
     * @return Rectangle
     */
    public function movePivot(string $position, int $offset_x = 0, int $offset_y = 0): self
    {
        switch (strtolower($position)) {
            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $x = intval($this->getWidth() / 2);
                $y = 0 + $offset_y;
                break;

            case 'top-right':
            case 'right-top':
                $x = $this->getWidth() - $offset_x;
                $y = 0 + $offset_y;
                break;

            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $x = 0 + $offset_x;
                $y = intval($this->getHeight() / 2);
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $x = $this->getWidth() - $offset_x;
                $y = intval($this->getHeight() / 2);
                break;

            case 'bottom-left':
            case 'left-bottom':
                $x = 0 + $offset_x;
                $y = $this->getHeight() - $offset_y;
                break;

            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $x = intval($this->getWidth() / 2);
                $y = $this->getHeight() - $offset_y;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $x = $this->getWidth() - $offset_x;
                $y = $this->getHeight() - $offset_y;
                break;

            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $x = intval($this->getWidth() / 2) + $offset_x;
                $y = intval($this->getHeight() / 2) + $offset_y;
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

    public function alignPivotTo(SizeInterface $size, string $position): self
    {
        $reference = new self($size->getWidth(), $size->getHeight());
        $reference->movePivot($position);

        $this->movePivot($position)->setPivot(
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
            $this->getPivot()->getX() - $rectangle->getPivot()->getX(),
            $this->getPivot()->getY() - $rectangle->getPivot()->getY()
        );
    }

    public function getAspectRatio(): float
    {
        return $this->getWidth() / $this->getHeight();
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

    public function topLeftPoint(): PointInterface
    {
        return $this->points[0];
    }

    public function bottomRightPoint(): PointInterface
    {
        return $this->points[2];
    }

    protected function getResizer(?int $width = null, ?int $height = null): RectangleResizer
    {
        return new RectangleResizer($width, $height);
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
