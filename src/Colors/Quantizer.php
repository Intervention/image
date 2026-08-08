<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;

class Quantizer
{
    public function __construct(protected int $levels = 8)
    {
        if ($this->levels < 1 || $this->levels > 256) {
            throw new InvalidArgumentException('Quantization levels must be between 1 and 256');
        }
    }

    /**
     * Convert a normalized value [0, 1] to a bin index.
     */
    public function quantize(float $value): int
    {
        if ($value < 0 || $value > 1) {
            throw new InvalidArgumentException('Value must be normalized between 0 and 1');
        }

        $value = max(0.0, min(1.0, $value));
        $bin = (int) floor($value * $this->levels); // 1.0 belongs to the last bin.

        return min($bin, $this->levels - 1);
    }

    /**
     * Convert a bin index to the center value of that bin.
     */
    public function dequantize(int $bin): float
    {
        $bin = max(0, min($this->levels - 1, $bin));

        return ($bin + 0.5) / $this->levels;
    }
}
