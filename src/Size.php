<?php

declare(strict_types=1);

namespace Intervention\Image;

use ArrayIterator;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Tools\Resizer;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Traversable;

class Size extends Polygon implements SizeInterface
{
    /**
     * Create new rectangle instance.
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public function __construct(
        int $width,
        int $height,
        protected PointInterface $pivot = new Point()
    ) {
        if ($width < 0) {
            throw new InvalidArgumentException(
                'Width of ' . $this::class . ' must be greater than or equal to 0'
            );
        }

        if ($height < 0) {
            throw new InvalidArgumentException(
                'Height of ' . $this::class . ' must be greater than or equal to 0'
            );
        }

        $this->addPoint(new Point($this->pivot->x(), $this->pivot->y()));
        $this->addPoint(new Point($this->pivot->x() + $width, $this->pivot->y()));
        $this->addPoint(new Point($this->pivot->x() + $width, $this->pivot->y() - $height));
        $this->addPoint(new Point($this->pivot->x(), $this->pivot->y() - $height));
    }

    /**
     * Set size of rectangle.
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
     * @see DrawableInterface::setPosition()
     */
    public function setPosition(PointInterface $position): self
    {
        parent::setPosition($position);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::movePivot()
     *
     * @throws InvalidArgumentException
     */
    public function movePivot(string|Alignment $position, int $x = 0, int $y = 0): self
    {
        $point = match (Alignment::tryCreate($position)) {
            Alignment::TOP => new Point(
                intval(round($this->width() / 2)) + $x,
                $y,
            ),
            Alignment::TOP_RIGHT => new Point(
                $this->width() - $x,
                $y,
            ),
            Alignment::LEFT => new Point(
                $x,
                intval(round($this->height() / 2)) + $y,
            ),
            Alignment::RIGHT => new Point(
                $this->width() - $x,
                intval(round($this->height() / 2)) + $y,
            ),
            Alignment::BOTTOM_LEFT => new Point(
                $x,
                $this->height() - $y,
            ),
            Alignment::BOTTOM => new Point(
                intval(round($this->width() / 2)) + $x,
                $this->height() - $y,
            ),
            Alignment::BOTTOM_RIGHT => new Point(
                $this->width() - $x,
                $this->height() - $y,
            ),
            Alignment::CENTER => new Point(
                intval(round($this->width() / 2)) + $x,
                intval(round($this->height() / 2)) + $y,
            ),
            Alignment::TOP_LEFT => new Point(
                $x,
                $y,
            ),
            default => new Point(
                $x,
                $y,
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
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
     */
    public function resize(?int $width = null, ?int $height = null): SizeInterface
    {
        try {
            return $this->resizer($width, $height)->resize($this);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                'Invalid target size ' . $width . 'x' . $height,
                previous: $e,
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::resizeDown()
     *
     * @throws InvalidArgumentException
     */
    public function resizeDown(?int $width = null, ?int $height = null): SizeInterface
    {
        try {
            return $this->resizer($width, $height)->resizeDown($this);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                'Invalid target size ' . $width . 'x' . $height,
                previous: $e,
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::scale()
     *
     * @throws InvalidArgumentException
     */
    public function scale(?int $width = null, ?int $height = null): SizeInterface
    {
        try {
            return $this->resizer($width, $height)->scale($this);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                'Invalid target size ' . $width . 'x' . $height,
                previous: $e,
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::scaleDown()
     *
     * @throws InvalidArgumentException
     */
    public function scaleDown(?int $width = null, ?int $height = null): SizeInterface
    {
        try {
            return $this->resizer($width, $height)->scaleDown($this);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                'Invalid target size ' . $width . 'x' . $height,
                previous: $e,
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::cover()
     *
     * @throws InvalidArgumentException
     */
    public function cover(int $width, int $height): SizeInterface
    {
        try {
            return $this->resizer($width, $height)->cover($this);
        } catch (InvalidArgumentException | StateException $e) {
            throw new InvalidArgumentException(
                'Invalid target size ' . $width . 'x' . $height,
                previous: $e,
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::contain()
     *
     * @throws InvalidArgumentException
     */
    public function contain(int $width, int $height): SizeInterface
    {
        try {
            return $this->resizer($width, $height)->contain($this);
        } catch (StateException $e) {
            throw new InvalidArgumentException(
                'Invalid target size ' . $width . 'x' . $height,
                previous: $e,
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::containDown()
     *
     * @throws InvalidArgumentException
     */
    public function containDown(int $width, int $height): SizeInterface
    {
        try {
            return $this->resizer($width, $height)->containDown($this);
        } catch (StateException $e) {
            throw new InvalidArgumentException(
                'Invalid target size ' . $width . 'x' . $height,
                previous: $e,
            );
        }
    }

    /**
     * Create resizer instance with given target size.
     *
     * @throws InvalidArgumentException
     */
    protected function resizer(?int $width = null, ?int $height = null): Resizer
    {
        return new Resizer($width, $height);
    }

    /**
     * Implement iteration.
     *
     * @return Traversable<mixed>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator([$this->width(), $this->height()]);
    }

    /**
     * Show debug info for the current rectangle.
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
