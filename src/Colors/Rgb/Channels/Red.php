<?php

namespace Intervention\Image\Colors\Rgb\Channels;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorChannelInterface;

class Red implements ColorChannelInterface
{
    protected int $value;

    /**
     * Create and validate new instance
     *
     * @param  int $value
     */
    public function __construct(int $value)
    {
        $this->value = $this->validate($value);
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
