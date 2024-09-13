<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Geometry\Tools\RectangleResizer;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Rectangle extends Polygon implements SizeInterface
{
    /**
     * Create new rectangle instance
     *
     * @param int $width
     * @param int $height
     * @param PointInterface $pivot
     * @return void
     */
    public function __construct(
        int $width,
        int $height,
        protected PointInterface $pivot = new Point()
    ) {
        $this->addPoint(new Point($this->pivot->x(), $this->pivot->y()));
        $this->addPoint(new Point($this->pivot->x() + $width, $this->pivot->y()));
        $this->addPoint(new Point($this->pivot->x() + $width, $this->pivot->y() - $height));
        $this->addPoint(new Point($this->pivot->x(), $this->pivot->y() - $height));
    }

    /**
     * Set size of rectangle
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
     * Set width of rectangle
     *
     * @param int $width
     * @return Rectangle
     */
    public function setWidth(int $width): self
    {
        $this[1]->setX($this[0]->x() + $width);
        $this[2]->setX($this[3]->x() + $width);

        return $this;
    }

    /**
     * Set height of rectangle
     *
     * @param int $height
     * @return Rectangle
     */
    public function setHeight(int $height): self
    {
        $this[2]->setY($this[1]->y() + $height);
        $this[3]->setY($this[0]->y() + $height);

        return $this;
    }

    /**
     * Return pivot point of rectangle
     *
     * @return PointInterface
     */
    public function pivot(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * Set pivot point of rectangle
     *
     * @param PointInterface $pivot
     * @return Rectangle
     */
    public function setPivot(PointInterface $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

    /**
     * Move pivot to the given position in the rectangle and adjust the new
     * position by given offset values.
     *
     * @param string $position
     * @param int $offset_x
     * @param int $offset_y
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
                $x = intval(round($this->width() / 2)) + $offset_x;
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
                $y = intval(round($this->height() / 2)) + $offset_y;
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $x = $this->width() - $offset_x;
                $y = intval(round($this->height() / 2)) + $offset_y;
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
                $x = intval(round($this->width() / 2)) + $offset_x;
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
                $x = intval(round($this->width() / 2)) + $offset_x;
                $y = intval(round($this->height() / 2)) + $offset_y;
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

    /**
     * Align pivot relative to given size at given position
     *
     * @param SizeInterface $size
     * @param string $position
     * @return Rectangle
     */
    public function alignPivotTo(SizeInterface $size, string $position): self
    {
        $reference = new self($size->width(), $size->height());
        $reference->movePivot($position);

        $this->movePivot($position)->setPivot(
            $reference->relativePositionTo($this)
        );

        return $this;
    }

    /**
     * Return relative position to given rectangle
     *
     * @param SizeInterface $rectangle
     * @return PointInterface
     */
    public function relativePositionTo(SizeInterface $rectangle): PointInterface
    {
        return new Point(
            $this->pivot()->x() - $rectangle->pivot()->x(),
            $this->pivot()->y() - $rectangle->pivot()->y()
        );
    }

    /**
     * Return aspect ration of rectangle
     *
     * @return float
     */
    public function aspectRatio(): float
    {
        return $this->width() / $this->height();
    }

    /**
     * Determine if rectangle fits into given rectangle
     *
     * @param SizeInterface $size
     * @return bool
     */
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
     * Determine if rectangle has landscape format
     *
     * @return bool
     */
    public function isLandscape(): bool
    {
        return $this->width() > $this->height();
    }

    /**
     * Determine if rectangle has landscape format
     *
     * @return bool
     */
    public function isPortrait(): bool
    {
        return $this->width() < $this->height();
    }

    /**
     * Return most top left point of rectangle
     *
     * @return PointInterface
     */
    public function topLeftPoint(): PointInterface
    {
        return $this->points[0];
    }

    /**
     * Return bottom right point of rectangle
     *
     * @return PointInterface
     */
    public function bottomRightPoint(): PointInterface
    {
        return $this->points[2];
    }

    /**
     * @see SizeInterface::resize()
     *
     * @throws GeometryException
     */
    public function resize(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->resize($this);
    }

    /**
     * @see SizeInterface::resizeDown()
     *
     * @throws GeometryException
     */
    public function resizeDown(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->resizeDown($this);
    }

    /**
     * @see SizeInterface::scale()
     *
     * @throws GeometryException
     */
    public function scale(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->scale($this);
    }

    /**
     * @see SizeInterface::scaleDown()
     *
     * @throws GeometryException
     */
    public function scaleDown(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->scaleDown($this);
    }

    /**
     * @see SizeInterface::cover()
     *
     * @throws GeometryException
     */
    public function cover(int $width, int $height): SizeInterface
    {
        return $this->resizer($width, $height)->cover($this);
    }

    /**
     * @see SizeInterface::contain()
     *
     * @throws GeometryException
     */
    public function contain(int $width, int $height): SizeInterface
    {
        return $this->resizer($width, $height)->contain($this);
    }

    /**
     * @see SizeInterface::containMax()
     *
     * @throws GeometryException
     */
    public function containMax(int $width, int $height): SizeInterface
    {
        return $this->resizer($width, $height)->containDown($this);
    }

    /**
     * Create resizer instance with given target size
     *
     * @param null|int $width
     * @param null|int $height
     * @throws GeometryException
     * @return RectangleResizer
     */
    protected function resizer(?int $width = null, ?int $height = null): RectangleResizer
    {
        return new RectangleResizer($width, $height);
    }
}
