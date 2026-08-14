<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Traits\CanHashColor;

class Quantizer
{
    use CanHashColor;

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
     * Build a palette by grouping the given colors into quantized bins.
     *
     * Each bin is represented by the first actual color assigned to it, so the
     * palette only contains colors that really occur in the source data. Alpha
     * is left out of the bin key to prevent visually identical colors from
     * occupying separate bins.
     *
     * @param array<ColorInterface> $colors
     * @throws ColorException
     */
    public function quantizeColors(array $colors): PaletteInterface
    {
        $palette = new Palette();
        $representatives = [];

        foreach ($colors as $color) {
            $key = $this->hashColor($this->quantizeColor($color)->withTransparency(1.0));
            $representatives[$key] ??= $color;
            $palette->addColor($representatives[$key]);
        }

        return $palette;
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
        } catch (InvalidArgumentException $e) {
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

        $bin = (int) floor($value * $this->levels); // 1.0 belongs to the last bin.

        return min($bin, $this->levels - 1);
    }

    /**
     * Convert a bin index to the center value of that bin.
     */
    private function binIndexToNormalized(int $bin): float
    {
        $bin = max(0, min($this->levels - 1, $bin));

        // levels is always >= 1, division by zero is impossible
        // @phpstan-ignore missingType.checkedException
        return ($bin + 0.5) / $this->levels;
    }
}
