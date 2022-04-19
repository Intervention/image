<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\Abstract\AbstractColor;
use Intervention\Image\Interfaces\ColorInterface;

class Color extends AbstractColor implements ColorInterface
{
    public function __construct(protected int $value = 0)
    {
        //
    }

    public function red(): int
    {
        return $this->toArray()[0];
    }

    public function green(): int
    {
        return $this->toArray()[1];
    }

    public function blue(): int
    {
        return $this->toArray()[2];
    }

    public function alpha(): float
    {
        return $this->toArray()[3];
    }

    public function toArray(): array
    {
        $a = ($this->value >> 24) & 0x7F;
        $r = ($this->value >> 16) & 0xFF;
        $g = ($this->value >> 8) & 0xFF;
        $b = $this->value & 0xFF;
        $a = (float) round(1 - $a / 127, 2);

        return [$r, $g, $b, $a];
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
