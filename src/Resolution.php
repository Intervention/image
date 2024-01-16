<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\ResolutionInterface;

class Resolution implements ResolutionInterface
{
    public const PER_INCH = 1;
    public const PER_CM = 2;

    /**
     * Create new instance
     *
     * @param float $x
     * @param float $y
     * @param int $per_unit
     */
    public function __construct(
        protected float $x,
        protected float $y,
        protected int $per_unit = self::PER_INCH
    ) {
        //
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
     * @see ResolutionInterface::setPerUnit()
     */
    protected function setPerUnit(int $per_unit): self
    {
        $this->per_unit = $per_unit;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::unit()
     */
    public function unit(): string
    {
        return match ($this->per_unit) {
            self::PER_CM => 'dpcm',
            default => 'dpi',
        };
    }

    /**
     * {@inheritdoc}
     *
     * @see ResolutionInterface::perInch()
     */
    public function perInch(): self
    {
        return match ($this->per_unit) {
            self::PER_CM => $this
                ->setPerUnit(self::PER_INCH)
                ->setX($this->x * (1 / 2.54))
                ->setY($this->y * (1 / 2.54)),
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
        return match ($this->per_unit) {
            self::PER_INCH => $this
                ->setPerUnit(self::PER_CM)
                ->setX($this->x / (1 / 2.54))
                ->setY($this->y / (1 / 2.54)),
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
        return sprintf("%1\$.2f x %2\$.2f %3\$s", $this->x, $this->y, $this->unit());
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
