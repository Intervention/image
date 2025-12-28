<?php

declare(strict_types=1);

namespace Intervention\Image;

use ArrayIterator;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ResolutionInterface;
use IteratorAggregate;
use Stringable;
use Traversable;

/**
 * @implements IteratorAggregate<float>
 */
class Resolution implements ResolutionInterface, Stringable, IteratorAggregate
{
    /**
     * Create new instance
     */
    public function __construct(
        protected float $x,
        protected float $y,
        protected Length $unit = Length::INCH
    ) {
        if ($x < 0) {
            throw new InvalidArgumentException(
                'The value of the X-axis for ' . $this::class . ' must be greater or equal to 0',
            );
        }

        if ($y < 0) {
            throw new InvalidArgumentException(
                'The value of the Y-axis for ' . $this::class . ' must be greater or equal to 0',
            );
        }
    }

    /**
     * Static factory method to create new resolution instance
     */
    public static function create(float $x, float $y, Length $unit = Length::INCH): self
    {
        return new self($x, $y, $unit);
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator([$this->x, $this->y]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::x()
     */
    public function x(): float
    {
        return $this->x;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::setX()
     */
    public function setX(float $x): self
    {
        $this->x = $x;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::y()
     */
    public function y(): float
    {
        return $this->y;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::setY()
     */
    public function setY(float $y): self
    {
        $this->y = $y;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::setUnit()
     */
    protected function setUnit(Length $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::unit()
     */
    public function unit(): Length
    {
        return $this->unit;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::perInch()
     */
    public function perInch(): self
    {
        return match ($this->unit) {
            Length::CM => $this
                ->setUnit(Length::INCH)
                ->setX($this->x * 2.54)
                ->setY($this->y * 2.54),
            default => $this
        };
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::perCm()
     */
    public function perCm(): self
    {
        return match ($this->unit) {
            Length::INCH => $this
                ->setUnit(Length::CM)
                ->setX($this->x / 2.54)
                ->setY($this->y / 2.54),
            default => $this,
        };
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::toString()
     */
    public function toString(): string
    {
        return sprintf(
            "%1\$.2f x %2\$.2f %3\$s",
            $this->x,
            $this->y,
            match ($this->unit) {
                Length::INCH => 'dpi',
                Length::CM => 'dpcm',
                default => '',
            },
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::__toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
