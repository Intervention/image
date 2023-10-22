<?php

namespace Intervention\Image\Colors\Cmyk\Channels;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorChannelInterface;

class Cyan implements ColorChannelInterface
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

    public function value(): int
    {
        return $this->value;
    }

    public function normalize($precision = 32): float
    {
        return round($this->value() / $this->max(), $precision);
    }

    public function min(): int
    {
        return 0;
    }

    public function max(): int
    {
        return 100;
    }

    public function validate(mixed $value): mixed
    {
        if ($value < $this->min() || $value > $this->max()) {
            throw new ColorException('CMYK color values must be in range 0-100.');
        }

        return $value;
    }

    public function toString(): string
    {
        return (string) $this->value();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
