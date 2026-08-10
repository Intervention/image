<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
use Intervention\Image\Interfaces\ColorInterface;

class Classifier
{
    /**
     * Classification categories.
     */
    private const VIBRANT = 'vibrant';
    private const MUTED = 'muted';
    private const DARK_VIBRANT = 'darkVibrant';
    private const DARK_MUTED = 'darkMuted';
    private const LIGHT_VIBRANT = 'lightVibrant';
    private const LIGHT_MUTED = 'lightMuted';

    /**
     * HSL thresholds for category classification.
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
     * Scoring weights.
     */
    private const WEIGHT_SATURATION = 3.0;
    private const WEIGHT_LIGHTNESS = 6.0;
    private const WEIGHT_POPULATION = 1.0;

    /**
     * Create instance.
     *
     * @param array<PaletteColor> $colors
     */
    public function __construct(protected array $colors)
    {
        //
    }

    public function vibrant(): ?ColorInterface
    {
        return $this->findBestColor(self::VIBRANT);
    }

    public function muted(): ?ColorInterface
    {
        return $this->findBestColor(self::MUTED);
    }

    public function darkVibrant(): ?ColorInterface
    {
        return $this->findBestColor(self::DARK_VIBRANT);
    }

    public function darkMuted(): ?ColorInterface
    {
        return $this->findBestColor(self::DARK_MUTED);
    }

    public function lightVibrant(): ?ColorInterface
    {
        return $this->findBestColor(self::LIGHT_VIBRANT);
    }

    public function lightMuted(): ?ColorInterface
    {
        return $this->findBestColor(self::LIGHT_MUTED);
    }

    /**
     * Find the best color for a specific category
     */
    private function findBestColor(string $category): ?ColorInterface
    {
        $bestScore = 0.0;
        $bestColor = null;
        $totalPopulation = $this->totalPopulation();

        foreach ($this->colors as $color) {
            $hslColor = $color->toColorspace(Hsl::class);

            if (!$this->isColorInCategory($hslColor, $category)) {
                continue;
            }

            $score = $this->calculateScore(
                $hslColor,
                $color->population,
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

    private function totalPopulation(): int
    {
        $samplePopulation = 0;
        foreach ($this->colors as $color) {
            $samplePopulation += $color->population;
        }

        return $samplePopulation;
    }

    /**
     * Check if color matches category criteria.
     */
    private function isColorInCategory(HslColor $color, string $category): bool
    {
        $saturation = $color->saturation()->normalized();
        $lightness = $color->luminance()->normalized();

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
     * Calculate score for a color based on category targets.
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

        // get target values for this category
        [$targetSaturation, $targetLightness] = $this->getCategoryTargets($category);

        // calculate distances from target values
        $saturationScore = 1.0 - abs($saturation - $targetSaturation);
        $lightnessScore = 1.0 - abs($lightness - $targetLightness);

        // weighted score
        return (
            ($saturationScore * self::WEIGHT_SATURATION) +
            ($lightnessScore * self::WEIGHT_LIGHTNESS) +
            ($populationRatio * self::WEIGHT_POPULATION)
        );
    }

    /**
     * Get ideal saturation and lightness targets for each category.
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
