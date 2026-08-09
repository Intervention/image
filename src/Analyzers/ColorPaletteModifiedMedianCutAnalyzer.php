<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Generator;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ColorPaletteModifiedMedianCutAnalyzer extends ColorPaletteAnalyzer
{
    /**
     * Number of bits to use for color quantization (5 = 32 levels per channel).
     */
    private const COLOR_BITS = 5;

    /**
     * Multiplier for color quantization.
     */
    private const COLOR_MULTIPLIER = 8; // 256 / 32

    /**
     * Maximum iterations for Vbox splitting.
     */
    private const MAX_ITERATIONS = 1000;

    /**
     * Fraction of palette to generate in first pass.
     */
    private const FRACT_BY_POPULATION = 0.75;

    /**
     * Create new instance.
     */
    public function __construct(protected int $maxColors = 16)
    {
        $this->maxColors = max(1, $maxColors);
    }

    /**
     * Analyze image and extract color palette using modified median cut algorithm.
     *
     * @throws \Intervention\Image\Exceptions\InvalidArgumentException
     * @return array<ColorInterface>
     */
    public function analyze(ImageInterface $image): array
    {
        $pixels = $this->collectPixels($image);
        $histogram = $this->buildHistogram($pixels);

        if (empty($histogram)) {
            return [];
        }

        // Create initial Vbox containing all colors
        $vbox = $this->initialVbox($histogram);

        // Split Vboxes to create palette
        $vboxes = $this->splitVboxes($histogram, $vbox);

        // Extract colors from Vboxes
        return $this->extractColorsFromVboxes($vboxes, $histogram);
    }

    /**
     * Build 3D color histogram from pixels.
     *
     * @param Generator<ColorInterface> $pixels
     * @return array<int, int> Histogram mapping color index to pixel count
     */
    private function buildHistogram(Generator $pixels): array
    {
        $histogram = [];

        foreach ($pixels as $color) {
            // convert color to RGB
            $rgb = $color->toColorspace(Rgb::class);

            // quantize RGB values to 5 bits (0-31 range)
            $r = (int) floor($rgb->red()->value() / self::COLOR_MULTIPLIER);
            $g = (int) floor($rgb->green()->value() / self::COLOR_MULTIPLIER);
            $b = (int) floor($rgb->blue()->value() / self::COLOR_MULTIPLIER);

            // create single index from RGB components
            $index = $this->getColorIndex($r, $g, $b);

            if (!isset($histogram[$index])) {
                $histogram[$index] = 0;
            }

            $histogram[$index]++;
        }

        return $histogram;
    }

    /**
     * Get single index from RGB components.
     */
    private function getColorIndex(int $r, int $g, int $b): int
    {
        return ($r << (2 * self::COLOR_BITS)) + ($g << self::COLOR_BITS) + $b;
    }

    /**
     * Create initial Vbox containing all colors.
     *
     * @param array<int, int> $histogram
     * @return array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int}
     */
    private function initialVbox(array $histogram): array
    {
        $rMin = 32;
        $rMax = 0;
        $gMin = 32;
        $gMax = 0;
        $bMin = 32;
        $bMax = 0;

        foreach (array_keys($histogram) as $index) {
            $r = ($index >> (2 * self::COLOR_BITS)) & 31;
            $g = ($index >> self::COLOR_BITS) & 31;
            $b = $index & 31;

            $rMin = min($rMin, $r);
            $rMax = max($rMax, $r);
            $gMin = min($gMin, $g);
            $gMax = max($gMax, $g);
            $bMin = min($bMin, $b);
            $bMax = max($bMax, $b);
        }

        return $this->vbox($rMin, $rMax, $gMin, $gMax, $bMin, $bMax, $histogram);
    }

    /**
     * Create a Vbox with given bounds.
     *
     * @param array<int, int> $histogram
     * @return array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int}
     */
    private function vbox(int $r1, int $r2, int $g1, int $g2, int $b1, int $b2, array $histogram): array
    {
        $vbox = [
            'r1' => $r1,
            'r2' => $r2,
            'g1' => $g1,
            'g2' => $g2,
            'b1' => $b1,
            'b2' => $b2,
            'volume' => 0,
            'count' => 0,
        ];

        $vbox['volume'] = $this->calculateVolume($vbox);
        $vbox['count'] = $this->calculateCount($vbox, $histogram);

        return $vbox;
    }

    /**
     * Calculate volume of Vbox
     *
     * @param array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int} $vbox
     */
    private function calculateVolume(array $vbox): int
    {
        return ($vbox['r2'] - $vbox['r1'] + 1) *
               ($vbox['g2'] - $vbox['g1'] + 1) *
               ($vbox['b2'] - $vbox['b1'] + 1);
    }

    /**
     * Calculate pixel count in Vbox.
     *
     * @param array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int} $vbox
     * @param array<int, int> $histogram
     */
    private function calculateCount(array $vbox, array $histogram): int
    {
        $count = 0;

        for ($r = $vbox['r1']; $r <= $vbox['r2']; $r++) {
            for ($g = $vbox['g1']; $g <= $vbox['g2']; $g++) {
                for ($b = $vbox['b1']; $b <= $vbox['b2']; $b++) {
                    $index = $this->getColorIndex($r, $g, $b);
                    if (isset($histogram[$index])) {
                        $count += $histogram[$index];
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Split Vboxes to create color palette.
     *
     * @param array<int, int> $histogram
     * @param array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int} $initialVbox
     * @return array<array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int}>
     */
    private function splitVboxes(array $histogram, array $initialVbox): array
    {
        $queue1 = [$initialVbox];
        $queue2 = [];

        // first pass: split by population
        $target1 = (int) ceil(self::FRACT_BY_POPULATION * $this->maxColors);
        $this->performSplit($queue1, $histogram, $target1, true);

        // second pass: split by volume * population
        $queue2 = $queue1;
        $target2 = $this->maxColors - count($queue2);
        if ($target2 > 0) {
            $this->performSplit($queue2, $histogram, $target2, false);
        }

        return $queue2;
    }

    /**
     * Perform Vbox splitting.
     *
     * @param array<array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int}> &$queue
     * @param array<int, int> $histogram
     * @param int $target Target number of Vboxes
     * @param bool $sortByCount Sort by count (true) or by count * volume (false)
     */
    private function performSplit(array &$queue, array $histogram, int $target, bool $sortByCount): void
    {
        $iterations = 0;

        while (count($queue) < $target && $iterations < self::MAX_ITERATIONS) {
            $iterations++;

            // sort queue
            usort($queue, function (array $a, array $b) use ($sortByCount): int {
                if ($sortByCount) {
                    return $b['count'] <=> $a['count'];
                }
                return ($b['count'] * $b['volume']) <=> ($a['count'] * $a['volume']);
            });

            // take the first Vbox
            $vbox = array_shift($queue);

            if ($vbox === null || $vbox['count'] === 0) {
                break;
            }

            // try to split the Vbox
            $result = $this->medianCutApply($vbox, $histogram);

            if ($result === null) {
                // cannot split, put it back
                array_unshift($queue, $vbox);
                break;
            }

            // add the two new Vboxes
            array_unshift($queue, $result[0]);
            array_unshift($queue, $result[1]);
        }
    }

    /**
     * Apply median cut to split a Vbox.
     *
     * @param array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int} $vbox
     * @param array<int, int> $histogram
     * @return null|array{
     *     0: array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int},
     *     1: array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int}
     * }
     */
    private function medianCutApply(array $vbox, array $histogram): ?array
    {
        // find the dimension with the largest range
        $rRange = $vbox['r2'] - $vbox['r1'];
        $gRange = $vbox['g2'] - $vbox['g1'];
        $bRange = $vbox['b2'] - $vbox['b1'];

        $maxRange = max($rRange, $gRange, $bRange);

        // cannot split a Vbox of width 1
        if ($maxRange === 0) {
            return null;
        }

        // determine which dimension to split
        if ($maxRange === $rRange) {
            $cutPoint = $this->findCutPoint($vbox, $histogram, 'r');
            if ($cutPoint === null) {
                return null;
            }
            return [
                $this->vbox(
                    $vbox['r1'],
                    $cutPoint,
                    $vbox['g1'],
                    $vbox['g2'],
                    $vbox['b1'],
                    $vbox['b2'],
                    $histogram,
                ),
                $this->vbox(
                    $cutPoint + 1,
                    $vbox['r2'],
                    $vbox['g1'],
                    $vbox['g2'],
                    $vbox['b1'],
                    $vbox['b2'],
                    $histogram,
                ),
            ];
        } elseif ($maxRange === $gRange) {
            $cutPoint = $this->findCutPoint($vbox, $histogram, 'g');
            if ($cutPoint === null) {
                return null;
            }
            return [
                $this->vbox(
                    $vbox['r1'],
                    $vbox['r2'],
                    $vbox['g1'],
                    $cutPoint,
                    $vbox['b1'],
                    $vbox['b2'],
                    $histogram,
                ),
                $this->vbox(
                    $vbox['r1'],
                    $vbox['r2'],
                    $cutPoint + 1,
                    $vbox['g2'],
                    $vbox['b1'],
                    $vbox['b2'],
                    $histogram,
                ),
            ];
        } else {
            $cutPoint = $this->findCutPoint($vbox, $histogram, 'b');
            if ($cutPoint === null) {
                return null;
            }
            return [
                $this->vbox(
                    $vbox['r1'],
                    $vbox['r2'],
                    $vbox['g1'],
                    $vbox['g2'],
                    $vbox['b1'],
                    $cutPoint,
                    $histogram,
                ),
                $this->vbox(
                    $vbox['r1'],
                    $vbox['r2'],
                    $vbox['g1'],
                    $vbox['g2'],
                    $cutPoint + 1,
                    $vbox['b2'],
                    $histogram,
                ),
            ];
        }
    }

    /**
     * Find the cut point along a dimension.
     *
     * @param array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int} $vbox
     * @param array<int, int> $histogram
     * @param string $dimension Dimension to split ('r', 'g', or 'b')
     */
    private function findCutPoint(array $vbox, array $histogram, string $dimension): ?int
    {
        $dim1 = $dimension . '1';
        $dim2 = $dimension . '2';
        $start = $vbox[$dim1];
        $end = $vbox[$dim2];

        // count pixels along dimension
        $total = 0;
        $partialSum = [];

        for ($i = $start; $i <= $end; $i++) {
            $sum = $this->countAlongDimension($vbox, $histogram, $dimension, $i);
            $total += $sum;
            $partialSum[$i] = $total;
        }

        if ($total === 0) {
            return null;
        }

        // find median cut point
        $target = $total / 2;
        for ($i = $start; $i <= $end; $i++) {
            if ($partialSum[$i] >= $target) {
                // return the point that creates the most balanced split
                return $i;
            }
        }

        return $end;
    }

    /**
     * Count pixels at a specific position along a dimension.
     *
     * @param array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int} $vbox
     * @param array<int, int> $histogram
     * @param string $dimension Dimension to count along ('r', 'g', or 'b')
     * @param int $position Position along the dimension
     */
    private function countAlongDimension(array $vbox, array $histogram, string $dimension, int $position): int
    {
        $count = 0;

        if ($dimension === 'r') {
            for ($g = $vbox['g1']; $g <= $vbox['g2']; $g++) {
                for ($b = $vbox['b1']; $b <= $vbox['b2']; $b++) {
                    $index = $this->getColorIndex($position, $g, $b);
                    if (isset($histogram[$index])) {
                        $count += $histogram[$index];
                    }
                }
            }
        } elseif ($dimension === 'g') {
            for ($r = $vbox['r1']; $r <= $vbox['r2']; $r++) {
                for ($b = $vbox['b1']; $b <= $vbox['b2']; $b++) {
                    $index = $this->getColorIndex($r, $position, $b);
                    if (isset($histogram[$index])) {
                        $count += $histogram[$index];
                    }
                }
            }
        } else { // 'b'
            for ($r = $vbox['r1']; $r <= $vbox['r2']; $r++) {
                for ($g = $vbox['g1']; $g <= $vbox['g2']; $g++) {
                    $index = $this->getColorIndex($r, $g, $position);
                    if (isset($histogram[$index])) {
                        $count += $histogram[$index];
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Extract colors from Vboxes.
     *
     * @param array<array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int, volume: int, count: int}> $vboxes
     * @param array<int, int> $histogram
     * @throws \Intervention\Image\Exceptions\InvalidArgumentException
     * @return array<ColorInterface>
     */
    private function extractColorsFromVboxes(array $vboxes, array $histogram): array
    {
        $colors = [];

        foreach ($vboxes as $vbox) {
            $color = $this->getAverageColor($vbox, $histogram);
            if ($color !== null) {
                $colors[] = $color;
            }
        }

        return $colors;
    }

    /**
     * Get average color of a Vbox.
     *
     * @param array{r1: int, r2: int, g1: int, g2: int, b1: int, b2: int} $vbox
     * @param array<int, int> $histogram
     * @throws \Intervention\Image\Exceptions\InvalidArgumentException
     */
    private function getAverageColor(array $vbox, array $histogram): ?ColorInterface
    {
        $rTotal = 0;
        $gTotal = 0;
        $bTotal = 0;
        $count = 0;

        for ($r = $vbox['r1']; $r <= $vbox['r2']; $r++) {
            for ($g = $vbox['g1']; $g <= $vbox['g2']; $g++) {
                for ($b = $vbox['b1']; $b <= $vbox['b2']; $b++) {
                    $index = $this->getColorIndex($r, $g, $b);
                    if (isset($histogram[$index])) {
                        $pixelCount = $histogram[$index];
                        $count += $pixelCount;
                        $rTotal += $pixelCount * ($r + 0.5) * self::COLOR_MULTIPLIER;
                        $gTotal += $pixelCount * ($g + 0.5) * self::COLOR_MULTIPLIER;
                        $bTotal += $pixelCount * ($b + 0.5) * self::COLOR_MULTIPLIER;
                    }
                }
            }
        }

        if ($count === 0) {
            return null;
        }

        $rAvg = (int) round($rTotal / $count);
        $gAvg = (int) round($gTotal / $count);
        $bAvg = (int) round($bTotal / $count);

        // Clamp values to valid range
        $rAvg = max(0, min(255, $rAvg));
        $gAvg = max(0, min(255, $gAvg));
        $bAvg = max(0, min(255, $bAvg));

        return Rgb::colorFromNormalized([
            $rAvg / 255,
            $gAvg / 255,
            $bAvg / 255,
            1.0,
        ]);
    }
}
