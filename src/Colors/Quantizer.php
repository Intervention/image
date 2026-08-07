<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;

class Quantizer
{
    protected int $levels;

    public function __construct(int $precision = 65)
    {
        if ($precision < 0 || $precision > 100) {
            throw new InvalidArgumentException('Quantization precision must be between 0 and 100');
        }

        $this->levels = (int) round(2 + 254 * (exp(0.1067982567 * $precision) - 1) / (exp(0.1067982567 * 100) - 1));
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
