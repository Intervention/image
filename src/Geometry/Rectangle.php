<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Geometry\Tools\RectangleResizer;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Rectangle extends Polygon implements SizeInterface, DrawableInterface
{
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

    public function setSize(int $width, int $height): self
    {
        return $this->setWidth($width)->setHeight($height);
    }

    public function setWidth(int $width): self
    {
        $this[1]->setX($this[0]->x() + $width);
        $this[2]->setX($this[3]->x() + $width);

        return $this;
    }

    public function setHeight(int $height): self
    {
        $this[2]->setY($this[1]->y() + $height);
        $this[3]->setY($this[0]->y() + $height);

        return $this;
    }

    public function pivot(): PointInterface
    {
        return $this->pivot;
    }

    public function setPivot(PointInterface $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

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

    public function alignPivotTo(SizeInterface $size, string $position): self
    {
        $reference = new self($size->width(), $size->height());
        $reference->movePivot($position);

        $this->movePivot($position)->setPivot(
            $reference->relativePositionTo($this)
        );

        return $this;
    }

    public function relativePositionTo(SizeInterface $rectangle): PointInterface
    {
        return new Point(
            $this->pivot()->x() - $rectangle->pivot()->x(),
            $this->pivot()->y() - $rectangle->pivot()->y()
        );
    }

    public function aspectRatio(): float
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

    public function isLandscape(): bool
    {
        return $this->width() > $this->height();
    }

    public function isPortrait(): bool
    {
        return $this->width() < $this->height();
    }

    public function topLeftPoint(): PointInterface
    {
        return $this->points[0];
    }

    public function bottomRightPoint(): PointInterface
    {
        return $this->points[2];
    }

    protected function resizer(?int $width = null, ?int $height = null): RectangleResizer
    {
        return new RectangleResizer($width, $height);
    }

    public function resize(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->resize($this);
    }

    public function resizeDown(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->resizeDown($this);
    }

    public function scale(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->scale($this);
    }

    public function scaleDown(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->scaleDown($this);
    }

    public function cover(int $width, int $height): SizeInterface
    {
        return $this->resizer($width, $height)->cover($this);
    }

    public function contain(int $width, int $height): SizeInterface
    {
        return $this->resizer($width, $height)->contain($this);
    }

    public function containMax(int $width, int $height): SizeInterface
    {
        return $this->resizer($width, $height)->containDown($this);
    }
}
