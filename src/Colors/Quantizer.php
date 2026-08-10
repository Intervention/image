<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;

class Quantizer
{
    public const int QUANTIZATION_LEVEL_MIN = 1;
    public const int QUANTIZATION_LEVEL_MAX = 256;
    public const int QUANTIZATION_LEVEL_DEFAULT = 16;

    public function __construct(protected int $levels = self::QUANTIZATION_LEVEL_DEFAULT)
    {
        if ($this->levels < self::QUANTIZATION_LEVEL_MIN || $this->levels > self::QUANTIZATION_LEVEL_MAX) {
            throw new InvalidArgumentException(
                'Quantization levels must be between ' .
                    self::QUANTIZATION_LEVEL_MIN . ' and ' .
                    self::QUANTIZATION_LEVEL_MAX,
            );
        }
    }

    /**
     * Return a quantized version of the given color.
     */
    public function quantizeColor(ColorInterface $color): QuantizedColor
    {
        // preserve alpha unquantized
        $alpha = $color->alpha()->normalized();

        $normalized = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalized(),
            $color->channels(),
        );

        $quantized = array_map(
            fn(float $normalized): int => $this->normalizedToBinIndex($normalized),
            $normalized,
        );

        $dequantized = array_map(
            fn(int $quantized): float => $this->binIndexToNormalized($quantized),
            $quantized,
        );

        // apply preserve alpha
        $dequantized[3] = $alpha;

        return new QuantizedColor($color->colorspace()->colorFromNormalized($dequantized));
    }

    /**
     * Convert a normalized value [0, 1] to a bin index.
     */
    private function normalizedToBinIndex(float $value): int
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
    private function binIndexToNormalized(int $bin): float
    {
        $bin = max(0, min($this->levels - 1, $bin));

        return ($bin + 0.5) / $this->levels;
    }
}
