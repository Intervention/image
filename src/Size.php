<?php

declare(strict_types=1);

namespace Intervention\Image;

use ArrayAccess;
use ArrayIterator;
use DivisionByZeroError;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Tools\Resizer;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Geometry\Point;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int>
 * @implements ArrayAccess<int|string, int>
 */
class Size implements SizeInterface, ArrayAccess, IteratorAggregate
{
    /**
     * Create new size instance.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected int $width,
        protected int $height,
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
    }

    /**
     * Create size statically.
     *
     * @throws InvalidArgumentException
     */
    public static function create(int $width, int $height, PointInterface $pivot = new Point()): self
    {
        return new self($width, $height, $pivot);
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::width()
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::height()
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Set current size.
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
        $this->width = $width;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SizeInterface::setHeight()
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

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
        return $this->setPivot($position);
    }

    /**
     * {@inheritdoc}
     *
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
     * @see SizeInterface::offsetTo()
     */
    public function offsetTo(SizeInterface $size): PointInterface
    {
        return new Point(
            $this->pivot()->x() - $size->pivot()->x(),
            $this->pivot()->y() - $size->pivot()->y()
        );
    }

    /**
     * {@inheritdoc}
     *
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
     * Return orientation of current size.
     */
    public function orientation(): Orientation
    {
        return Orientation::fromSize($this);
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
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     *
     * @return Traversable<mixed>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator([$this->width, $this->height]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists(mixed $offset): bool
    {
        return in_array($offset, [0, 1, 'width', 'height']);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     *
     * @throws RuntimeException
     */
    public function offsetGet(mixed $offset): mixed
    {
        return match ($offset) {
            0, 'width' => $this->width,
            1, 'height' => $this->height,
            default => throw new RuntimeException('Undefined array key ' . $offset)
        };
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     *
     * @throws RuntimeException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new RuntimeException('Unable to set array key, use setWidth() or setHeight()');
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     *
     * @throws RuntimeException
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException('Unable to unset array key');
    }

    /**
     * Show debug info for the current size.
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
