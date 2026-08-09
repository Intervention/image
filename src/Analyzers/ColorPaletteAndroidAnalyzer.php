<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ColorPaletteAndroidAnalyzer extends QuantizedPaletteAnalyzer
{
    /**
     * Palette category definitions (HSL-based)
     */
    private const VIBRANT = 'vibrant';
    private const MUTED = 'muted';
    private const DARK_VIBRANT = 'darkVibrant';
    private const DARK_MUTED = 'darkMuted';
    private const LIGHT_VIBRANT = 'lightVibrant';
    private const LIGHT_MUTED = 'lightMuted';

    /**
     * HSL thresholds for category classification
     */
    private const MIN_VIBRANT_SATURATION = 0.35;
    private const MIN_MUTED_SATURATION = 0.1;
    private const MAX_MUTED_SATURATION = 0.4;

    private const MIN_NORMAL_LIGHTNESS = 0.35;
    private const MAX_NORMAL_LIGHTNESS = 0.7;

    private const MIN_DARK_LIGHTNESS = 0.1;
    private const MAX_DARK_LIGHTNESS = 0.45;

    private const MIN_LIGHT_LIGHTNESS = 0.55;
    private const MAX_LIGHT_LIGHTNESS = 0.9;

    /**
     * Scoring weights
     */
    private const WEIGHT_SATURATION = 3.0;
    private const WEIGHT_LIGHTNESS = 6.0;
    private const WEIGHT_POPULATION = 1.0;

    /**
     * Analyze image and extract classified color palette.
     *
     * @return array<string, ColorInterface|null>
     */
    public function analyze(ImageInterface $image): array
    {
        $pixels = $this->collectPixels($image);
        $quantizedPixels = $this->quantizePixels($pixels, $this->quantizationLevel($image));

        // Calculate total population for normalization
        $totalPopulation = array_sum(array_column($quantizedPixels, 'count'));

        // Find best color for each category
        $colors = [
            self::VIBRANT => $this->findBestColor($quantizedPixels, $totalPopulation, self::VIBRANT),
            self::MUTED => $this->findBestColor($quantizedPixels, $totalPopulation, self::MUTED),
            self::DARK_VIBRANT => $this->findBestColor($quantizedPixels, $totalPopulation, self::DARK_VIBRANT),
            self::DARK_MUTED => $this->findBestColor($quantizedPixels, $totalPopulation, self::DARK_MUTED),
            self::LIGHT_VIBRANT => $this->findBestColor($quantizedPixels, $totalPopulation, self::LIGHT_VIBRANT),
            self::LIGHT_MUTED => $this->findBestColor($quantizedPixels, $totalPopulation, self::LIGHT_MUTED),
        ];

        return array_values($colors);
    }

    /**
     * Find the best color for a specific category
     *
     * @param array<array{color: ColorInterface, count: int}> $quantizedPixels
     */
    private function findBestColor(array $quantizedPixels, int $totalPopulation, string $category): ?ColorInterface
    {
        $bestScore = 0.0;
        $bestColor = null;

        foreach ($quantizedPixels as $pixel) {
            $color = $pixel['color'];
            $hslColor = $color->toColorspace(Hsl::class);

            if (!$this->isColorInCategory($hslColor, $category)) {
                continue;
            }

            $score = $this->calculateScore(
                $hslColor,
                $pixel['count'],
                $totalPopulation,
                $category,
            );

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestColor = $color;
            }
        }

        return $bestColor;
    }

    /**
     * Check if color matches category criteria
     */
    private function isColorInCategory(HslColor $color, string $category): bool
    {
        $saturation = $color->saturation()->value() / 100.0; // Normalize to 0-1
        $lightness = $color->luminance()->value() / 100.0; // Normalize to 0-1

        return match ($category) {
            self::VIBRANT => $saturation >= self::MIN_VIBRANT_SATURATION
                && $lightness >= self::MIN_NORMAL_LIGHTNESS
                && $lightness <= self::MAX_NORMAL_LIGHTNESS,

            self::MUTED => $saturation >= self::MIN_MUTED_SATURATION
                && $saturation <= self::MAX_MUTED_SATURATION
                && $lightness >= self::MIN_NORMAL_LIGHTNESS
                && $lightness <= self::MAX_NORMAL_LIGHTNESS,

            self::DARK_VIBRANT => $saturation >= self::MIN_VIBRANT_SATURATION
                && $lightness >= self::MIN_DARK_LIGHTNESS
                && $lightness <= self::MAX_DARK_LIGHTNESS,

            self::DARK_MUTED => $saturation >= self::MIN_MUTED_SATURATION
                && $saturation <= self::MAX_MUTED_SATURATION
                && $lightness >= self::MIN_DARK_LIGHTNESS
                && $lightness <= self::MAX_DARK_LIGHTNESS,

            self::LIGHT_VIBRANT => $saturation >= self::MIN_VIBRANT_SATURATION
                && $lightness >= self::MIN_LIGHT_LIGHTNESS
                && $lightness <= self::MAX_LIGHT_LIGHTNESS,

            self::LIGHT_MUTED => $saturation >= self::MIN_MUTED_SATURATION
                && $saturation <= self::MAX_MUTED_SATURATION
                && $lightness >= self::MIN_LIGHT_LIGHTNESS
                && $lightness <= self::MAX_LIGHT_LIGHTNESS,

            default => false,
        };
    }

    /**
     * Calculate score for a color based on category targets
     */
    private function calculateScore(
        HslColor $color,
        int $population,
        int $totalPopulation,
        string $category,
    ): float {
        $saturation = $color->saturation()->value() / 100.0;
        $lightness = $color->luminance()->value() / 100.0;
        $populationRatio = $population / $totalPopulation;

        // Get target values for this category
        [$targetSaturation, $targetLightness] = $this->getCategoryTargets($category);

        // Calculate distances from target values
        $saturationScore = 1.0 - abs($saturation - $targetSaturation);
        $lightnessScore = 1.0 - abs($lightness - $targetLightness);

        // Weighted score
        return (
            ($saturationScore * self::WEIGHT_SATURATION) +
            ($lightnessScore * self::WEIGHT_LIGHTNESS) +
            ($populationRatio * self::WEIGHT_POPULATION)
        );
    }

    /**
     * Get ideal saturation and lightness targets for each category
     *
     * @return array{float, float}
     */
    private function getCategoryTargets(string $category): array
    {
        return match ($category) {
            self::VIBRANT => [0.7, 0.5],
            self::MUTED => [0.25, 0.5],
            self::DARK_VIBRANT => [0.7, 0.3],
            self::DARK_MUTED => [0.25, 0.3],
            self::LIGHT_VIBRANT => [0.7, 0.7],
            self::LIGHT_MUTED => [0.25, 0.7],
            default => [0.5, 0.5],
        };
    }
}
