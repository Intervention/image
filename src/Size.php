<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Geometry\Tools\RectangleResizer;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;

class Size extends Polygon implements SizeInterface
{
    /**
     * Create new rectangle instance
     *
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
     */
    public function setSize(int $width, int $height): self
    {
        return $this->setWidth($width)->setHeight($height);
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::setWidth()
     */
    public function setWidth(int $width): self
    {
        $this[1]->setX($this[0]->x() + $width);
        $this[2]->setX($this[3]->x() + $width);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::setHeight()
     */
    public function setHeight(int $height): self
    {
        $this[2]->setY($this[1]->y() + $height);
        $this[3]->setY($this[0]->y() + $height);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::pivot()
     */
    public function pivot(): PointInterface
    {
        return $this->pivot;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::setPivot()
     */
    public function setPivot(PointInterface $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::movePivot()
     *
     * @throws RuntimeException
     */
    public function movePivot(string|Alignment $position, int $offset_x = 0, int $offset_y = 0): self
    {
        $point = match (Alignment::create($position)) {
            Alignment::TOP => new Point(
                intval(round($this->width() / 2)) + $offset_x,
                $offset_y,
            ),
            Alignment::TOP_RIGHT => new Point(
                $this->width() - $offset_x,
                $offset_y,
            ),
            Alignment::LEFT => new Point(
                $offset_x,
                intval(round($this->height() / 2)) + $offset_y,
            ),
            Alignment::RIGHT => new Point(
                $this->width() - $offset_x,
                intval(round($this->height() / 2)) + $offset_y,
            ),
            Alignment::BOTTOM_LEFT => new Point(
                $offset_x,
                $this->height() - $offset_y,
            ),
            Alignment::BOTTOM => new Point(
                intval(round($this->width() / 2)) + $offset_x,
                $this->height() - $offset_y,
            ),
            Alignment::BOTTOM_RIGHT => new Point(
                $this->width() - $offset_x,
                $this->height() - $offset_y,
            ),
            Alignment::CENTER => new Point(
                intval(round($this->width() / 2)) + $offset_x,
                intval(round($this->height() / 2)) + $offset_y,
            ),
            Alignment::TOP_LEFT => new Point(
                $offset_x,
                $offset_y,
            ),
        };

        $this->pivot->setPosition(...$point);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::alignPivotTo()
     *
     * @throws RuntimeException
     */
    public function alignPivotTo(SizeInterface $size, string|Alignment $position): self
    {
        $reference = new self($size->width(), $size->height());
        $reference->movePivot($position);

        $this->movePivot($position)->setPivot(
            $reference->relativePositionTo($this)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::relativePositionTo()
     */
    public function relativePositionTo(SizeInterface $rectangle): PointInterface
    {
        return new Point(
            $this->pivot()->x() - $rectangle->pivot()->x(),
            $this->pivot()->y() - $rectangle->pivot()->y()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::aspectRatio()
     */
    public function aspectRatio(): float
    {
        return $this->width() / $this->height();
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::fitsInto()
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
     * {@inheritdoc}
     *
     * @see SizeInterface::isLandscape()
     */
    public function isLandscape(): bool
    {
        return $this->width() > $this->height();
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::isPortrait()
     */
    public function isPortrait(): bool
    {
        return $this->width() < $this->height();
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::resize()
     *
     * @throws GeometryException
     */
    public function resize(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->resize($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::resizeDown()
     *
     * @throws GeometryException
     */
    public function resizeDown(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->resizeDown($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::scale()
     *
     * @throws GeometryException
     */
    public function scale(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->scale($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::scaleDown()
     *
     * @throws GeometryException
     */
    public function scaleDown(?int $width = null, ?int $height = null): SizeInterface
    {
        return $this->resizer($width, $height)->scaleDown($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::cover()
     *
     * @throws GeometryException
     */
    public function cover(int $width, int $height): SizeInterface
    {
        return $this->resizer($width, $height)->cover($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::contain()
     *
     * @throws GeometryException
     */
    public function contain(int $width, int $height): SizeInterface
    {
        return $this->resizer($width, $height)->contain($this);
    }

    /**
     * {@inheritdoc}
     *
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
     * @throws GeometryException
     */
    protected function resizer(?int $width = null, ?int $height = null): RectangleResizer
    {
        return new RectangleResizer($width, $height);
    }

    /**
     * Show debug info for the current rectangle
     *
     * @return array<string, int|object>
     */
    public function __debugInfo(): array
    {
        return [
            'width' => $this->width(),
            'height' => $this->height(),
            'pivot' => $this->pivot,
        ];
    }
}
