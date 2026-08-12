<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use DivisionByZeroError;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;

class Quantizer
{
    public const int LEVEL_MIN = 1;
    public const int LEVEL_MAX = 256;
    public const int LEVEL_DEFAULT = 16;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(protected int $levels = self::LEVEL_DEFAULT)
    {
        if ($this->levels < self::LEVEL_MIN || $this->levels > self::LEVEL_MAX) {
            throw new InvalidArgumentException(
                'Quantization levels must be between ' .
                    self::LEVEL_MIN . ' and ' .
                    self::LEVEL_MAX,
            );
        }
    }

    /**
     * Quantize all colors in given array.
     *
     * @param array<ColorInterface> $colors
     * @throws AnalyzerException
     * @return Histogram<ColorInterface>
     */
    public function quantizeColors(array $colors): Histogram
    {
        return Histogram::fromColors(array_map(
            fn(ColorInterface $color) => $this->quantizeColor($color),
            $colors,
        ));
    }

    /**
     * Return a quantized version of the given color.
     *
     * @throws ColorException
     */
    public function quantizeColor(ColorInterface $color): ColorInterface
    {
        try {
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
        } catch (InvalidArgumentException | RuntimeException $e) {
            throw new ColorException('Failed to quantize color', previous: $e);
        }

        // transform quantized channel values to color object
        $color = $color->colorspace()->colorFromNormalized($dequantized);

        // re-apply preserve alpha
        return $color->withTransparency($alpha);
    }

    /**
     * Convert a normalized value [0, 1] to a bin index.
     *
     * @throws InvalidArgumentException
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
     *
     * @throws RuntimeException
     */
    private function binIndexToNormalized(int $bin): float
    {
        $bin = max(0, min($this->levels - 1, $bin));

        try {
            return ($bin + 0.5) / $this->levels;
        } catch (DivisionByZeroError) {
            throw new RuntimeException('Division by zero');
        }
    }
}
