<?php

namespace Intervention\Image\Colors\Rgb\Channels;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorChannelInterface;

class Red implements ColorChannelInterface
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
        return 255;
    }

    public function validate(mixed $value): mixed
    {
        if ($value < $this->min() || $value > $this->max()) {
            throw new ColorException('RGB color values must be in range 0-255.');
        }

        return $value;
    }
}
