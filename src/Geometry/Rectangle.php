<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use ArrayIterator;
use DivisionByZeroError;
use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Geometry\Tools\Resizer;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Traversable;

class Rectangle extends Polygon implements DrawableInterface, SizeInterface
{
    /**
     * Create new rectangle.
     *
     * @throws InvalidArgumentException
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

        parent::__construct([
            new Point(0, 0),
            new Point($width, 0),
            new Point($width, $height * -1),
            new Point(0, $height * -1),
        ], $pivot);
    }

    /**
     * Create rectangle statically.
     *
     * @deprecated Use Intervention\Image\Size::class instead.
     *
     * @throws InvalidArgumentException
     */
    public static function create(int $width, int $height, PointInterface $pivot = new Point()): self
    {
        return new self($width, $height, $pivot);
    }

    /**
     * Calculate width of rectangle.
     */
    public function width(): int
    {
        return abs($this->mostLeftPoint()->x() - $this->mostRightPoint()->x());
    }

    /**
     * Calculate height of rectangle.
     */
    public function height(): int
    {
        return abs($this->mostBottomPoint()->y() - $this->mostTopPoint()->y());
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
     * @see DrawableInterface::setPosition()
     */
    public function setPosition(PointInterface $position): self
    {
        $this->pivot = $position;

        return $this;
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
     * @see DrawableInterface::factory()
     *
     * @throws RuntimeException
     */
    public function factory(): DrawableFactoryInterface
    {
        // @phpstan-ignore missingType.checkedException
        return new RectangleFactory($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::adjust()
     *
     * @throws RuntimeException
     */
    public function adjust(callable $adjustments): DrawableInterface
    {
        $factory = $this->factory();
        $adjustments($factory);

        return $factory->drawable();
    }

    /**
     * Set size of rectangle.
     *
     * @deprecated Use Intervention\Image\Size::class instead.
     */
    public function setSize(int $width, int $height): self
    {
        return $this->setWidth($width)->setHeight($height);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Intervention\Image\Size::class instead.
     * @see SizeInterface::aspectRatio()
     *
     * @throws RuntimeException
     */
    public function aspectRatio(): float
    {
        try {
            return $this->width() / $this->height();
        } catch (DivisionByZeroError) {
            throw new RuntimeException('Division by zero');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Intervention\Image\Size::class instead.
     * @see SizeInterface::fitsWithin()
     */
    public function fitsWithin(SizeInterface $size): bool
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
     * @deprecated Use Intervention\Image\Size::class instead.
     * @see SizeInterface::isLandscape()
     */
    public function isLandscape(): bool
    {
        return $this->width() > $this->height();
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Intervention\Image\Size::class instead.
     * @see SizeInterface::isPortrait()
     */
    public function isPortrait(): bool
    {
        return $this->width() < $this->height();
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Intervention\Image\Size::class instead.
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
     * @deprecated Use Intervention\Image\Size::class instead.
     * @see SizeInterface::movePivot()
     *
     * @throws InvalidArgumentException
     */
    public function movePivot(string|Alignment $alignment, int $x = 0, int $y = 0): self
    {
        $alignment = Alignment::create($alignment); // normalize alignment

        $point = match ($alignment) {
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
        };

        $this->pivot->setPosition(...$point);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Intervention\Image\Size::class instead.
     * @see SizeInterface::alignPivotTo()
     *
     * @throws InvalidArgumentException
     */
    public function alignPivotTo(SizeInterface $size, string|Alignment $alignment): self
    {
        $reference = new self($size->width(), $size->height());
        $reference->movePivot($alignment);

        $this->movePivot($alignment)->setPivot(
            $reference->offsetTo($this)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Intervention\Image\Size::class instead.
     * @see SizeInterface::offsetTo()
     */
    public function offsetTo(SizeInterface $rectangle): PointInterface
    {
        return new Point(
            $this->pivot()->x() - $rectangle->pivot()->x(),
            $this->pivot()->y() - $rectangle->pivot()->y()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Use Intervention\Image\Size::class instead.
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
     * @deprecated Use Intervention\Image\Size::class instead.
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
     * @deprecated Use Intervention\Image\Size::class instead.
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
     * @deprecated Use Intervention\Image\Size::class instead.
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
     * @deprecated Use Intervention\Image\Size::class instead.
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
     * @deprecated Use Intervention\Image\Size::class instead.
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
     * @deprecated Use Intervention\Image\Size::class instead.
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
     * @deprecated Use Intervention\Image\Size::class instead.
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
