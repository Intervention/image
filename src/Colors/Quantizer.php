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
    public const int QUANTIZATION_LEVEL_MIN = 1;
    public const int QUANTIZATION_LEVEL_MAX = 256;
    public const int QUANTIZATION_LEVEL_DEFAULT = 16;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(protected int $levels = self::QUANTIZATION_LEVEL_DEFAULT, protected ?int $limit = null)
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
     * @throws InvalidArgumentException
     */
    public static function usingColorLimit(int $limit): self
    {
        // todo: calibrate limit to level transformation
        return new self(match (true) {
            $limit <= 16 => 4,
            $limit <= 32 => 6,
            $limit <= 64 => 8,
            $limit <= 128 => 16,
            $limit <= 256 => 18,
            $limit <= 515 => 32,
            $limit <= 1000 => 128,
            default => 256,
        }, $limit);
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
     * @param array<ColorInterface> $colors
     * @throws AnalyzerException
     * @return array<RatedColor>
     */
    public function quantizeColors(array $colors): array
    {
        $quantized = [];

        foreach ($colors as $color) {
            try {
                $color = new RatedColor($this->quantizeColor($color));
            } catch (ColorException $e) {
                throw new AnalyzerException('Failed to quantize colors', previous: $e);
            }
            $key = $color->hash();

            if (!isset($quantized[$key])) {
                $quantized[$key] = $color;
            }

            $quantized[$key]->increaseRating();
        }

        if ($this->limit !== null) {
            return array_slice($quantized, 0, $this->limit);
        }

        return $quantized;
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
