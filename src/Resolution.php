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
     * Create new instance.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected float $x,
        protected float $y,
        protected Length $length = Length::INCH,
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
     * {@inheritdoc}
     *
     * @see ResolutionInterface::dpi()
     */
    public static function dpi(float $x, float $y): ResolutionInterface
    {
        return new self($x, $y, Length::INCH);
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::ppi()
     */
    public static function ppi(float $x, float $y): ResolutionInterface
    {
        return new self($x, $y, Length::CM);
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
     * @see ResolutionInterface::y()
     */
    public function y(): float
    {
        return $this->y;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::length()
     */
    public function length(): Length
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::perInch()
     */
    public function perInch(): self
    {
        return match ($this->length) {
            Length::CM => new self(
                $this->x * 2.54,
                $this->y * 2.54,
                Length::INCH,
            ),
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
        return match ($this->length) {
            Length::INCH => new self(
                $this->x / 2.54,
                $this->y / 2.54,
                Length::CM,
            ),
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
            match ($this->length) {
                Length::INCH => 'dpi',
                Length::CM => 'dpcm',
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
