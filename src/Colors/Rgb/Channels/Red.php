<?php

namespace Intervention\Image\Colors\Rgb\Channels;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorChannelInterface;

class Red implements ColorChannelInterface
{
    protected int $value;

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::__construct()
     */
    public function __construct(int $value = null, float $normalized = null)
    {
        $this->value = $this->validate(
            match (true) {
                is_null($value) && is_numeric($normalized) => intval(round($normalized * $this->max())),
                is_numeric($value) && is_null($normalized) => $value,
                default => throw new ColorException('Color channels must either have a value or a normalized value')
            }
        );
    }

    /**
     * Alias of value()
     *
     * @return int
     */
    public function toInt(): int
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::value()
     */
    public function value(): int
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::normalize()
     */
    public function normalize($precision = 32): float
    {
        return round($this->value() / $this->max(), $precision);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::min()
     */
    public function min(): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::max()
     */
    public function max(): int
    {
        return 255;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::validate()
     */
    public function validate(mixed $value): mixed
    {
        if ($value < $this->min() || $value > $this->max()) {
            throw new ColorException('RGB color values must be in range 0-255.');
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::toString()
     */
    public function toString(): string
    {
        return (string) $this->value();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::__toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
