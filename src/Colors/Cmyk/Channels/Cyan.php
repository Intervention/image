<?php

namespace Intervention\Image\Colors\Cmyk\Channels;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorChannelInterface;

class Cyan implements ColorChannelInterface
{
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $this->validate($value);
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
