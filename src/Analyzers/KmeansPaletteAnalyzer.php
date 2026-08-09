<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklab\Colorspace as OklabColorspace;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class KmeansPaletteAnalyzer extends QuantizedPaletteAnalyzer
{
    /**
     * Maximum iterations for K-means algorithm.
     */
    private const MAX_ITERATIONS = 50;

    /**
     * Convergence threshold (if centroids move less than this, stop early).
     */
    private const CONVERGENCE_THRESHOLD = 0.001;

    /**
     * Minimum cluster size percentage to include in results (filters out noise).
     */
    private const MIN_CLUSTER_SIZE_PERCENT = 1.0;

    /**
     * Fixed seed for deterministic results.
     */
    private const SEED = 1024;

    /**
     * Create new instance.
     */
    public function __construct(protected int $maxColors = 16)
    {
        $this->maxColors = max(1, $maxColors);
    }

    /**
     * @return array<ColorInterface>
     */
    public function analyze(ImageInterface $image): array
    {
        // set seed for deterministic results
        mt_srand(self::SEED);

        $pixels = $this->collectPixels($image);

        // convert all pixels to OKLab color space and collect as array
        $oklabPixels = [];
        $originalColors = [];
        foreach ($pixels as $color) {
            $oklabColor = $color->toColorspace(OklabColorspace::class);
            $oklabPixels[] = array_map(
                fn(ColorChannelInterface $channel) => $channel->value(),
                $oklabColor->channels(),
            );
            $originalColors[] = $color;
        }

        if (count($oklabPixels) === 0) {
            return [];
        }

        // perform K-means clustering
        $clusters = $this->kMeansClustering($oklabPixels);

        // convert centroids back to original colorspace
        $dominantColors = [];
        $totalPixels = count($oklabPixels);
        $minClusterSize = ($totalPixels * self::MIN_CLUSTER_SIZE_PERCENT) / 100;

        foreach ($clusters as $cluster) {
            // filter out very small clusters
            if ($cluster['size'] < $minClusterSize) {
                continue;
            }

            // create OKLab color from centroid
            $oklabColor = new OklabColor(...$cluster['centroid']);

            // convert back to the original image colorspace
            $dominantColor = $oklabColor->toColorspace($image->colorspace());
            $dominantColors[] = $dominantColor;
        }

        return $dominantColors;
    }

    /**
     * Perform K-means clustering on OKLab pixel data.
     *
     * @param array<array<float>> $pixels
     * @return array<array{centroid: array<float>, size: int}>
     */
    private function kMeansClustering(array $pixels): array
    {
        $k = min($this->maxColors, count($pixels));

        // initialize centroids using K-means++
        $centroids = $this->initializeCentroids($pixels, $k);
        $assignments = [];

        // iteratively refine clusters
        for ($iteration = 0; $iteration < self::MAX_ITERATIONS; $iteration++) {
            // assign pixels to nearest centroid
            $assignments = $this->assignClusters($pixels, $centroids);

            // calculate new centroids
            $newCentroids = $this->updateCentroids($pixels, $assignments, $k);

            // check for convergence
            if ($this->hasConverged($centroids, $newCentroids)) {
                break;
            }

            $centroids = $newCentroids;
        }

        // build result with cluster sizes
        $clusterSizes = array_count_values($assignments);
        $results = [];

        for ($i = 0; $i < $k; $i++) {
            $results[] = [
                'centroid' => $centroids[$i],
                'size' => $clusterSizes[$i] ?? 0,
            ];
        }

        // sort by cluster size (most dominant first)
        usort($results, fn(array $a, array $b): int => $b['size'] <=> $a['size']);

        return $results;
    }

    /**
     * Initialize centroids using K-means++ algorithm for better starting positions.
     *
     * @param array<array<float>> $pixels
     * @return array<array<float>>
     */
    private function initializeCentroids(array $pixels, int $k): array
    {
        $centroids = [];
        $pixelIndices = array_keys($pixels);

        // choose first centroid randomly (deterministic with seed)
        $firstIndex = $pixelIndices[array_rand($pixelIndices)];
        $centroids[] = $pixels[$firstIndex];

        // choose remaining centroids with probability proportional to distance from existing centroids
        for ($i = 1; $i < $k; $i++) {
            $distances = [];
            $sumDistances = 0.0;

            foreach ($pixels as $index => $pixel) {
                // find minimum distance to any existing centroid
                $minDistance = PHP_FLOAT_MAX;
                foreach ($centroids as $centroid) {
                    $distance = $this->euclideanDistance($pixel, $centroid);
                    $minDistance = min($minDistance, $distance);
                }

                $distances[$index] = $minDistance * $minDistance; // square for better spread
                $sumDistances += $distances[$index];
            }

            // choose next centroid with weighted probability (deterministic with seed)
            $target = (mt_rand() / mt_getrandmax()) * $sumDistances;
            $cumulative = 0.0;
            $chosenIndex = 0;

            foreach ($distances as $index => $distance) {
                $cumulative += $distance;
                if ($cumulative >= $target) {
                    $chosenIndex = $index;
                    break;
                }
            }

            $centroids[] = $pixels[$chosenIndex];
        }

        return $centroids;
    }

    /**
     * Assign each pixel to the nearest centroid.
     *
     * @param array<array<float>> $pixels
     * @param array<array<float>> $centroids
     * @return array<int>
     */
    private function assignClusters(array $pixels, array $centroids): array
    {
        $assignments = [];

        foreach ($pixels as $pixel) {
            $minDistance = PHP_FLOAT_MAX;
            $closestCluster = 0;

            foreach ($centroids as $index => $centroid) {
                $distance = $this->euclideanDistance($pixel, $centroid);
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestCluster = $index;
                }
            }

            $assignments[] = $closestCluster;
        }

        return $assignments;
    }

    /**
     * Calculate new centroid positions based on cluster assignments.
     *
     * @param array<array<float>> $pixels
     * @param array<int> $assignments
     * @return array<array<float>>
     */
    private function updateCentroids(array $pixels, array $assignments, int $k): array
    {
        $centroids = [];

        for ($i = 0; $i < $k; $i++) {
            $clusterPixels = [];

            foreach ($assignments as $pixelIndex => $cluster) {
                if ($cluster === $i) {
                    $clusterPixels[] = $pixels[$pixelIndex];
                }
            }

            if (count($clusterPixels) === 0) {
                // if cluster is empty, reinitialize randomly
                $randomIndex = array_rand($pixels);
                $centroids[$i] = $pixels[$randomIndex];
            } else {
                // calculate mean of all pixels in cluster
                $sumL = 0.0;
                $sumA = 0.0;
                $sumB = 0.0;

                foreach ($clusterPixels as $pixel) {
                    $sumL += $pixel[0];
                    $sumA += $pixel[1];
                    $sumB += $pixel[2];
                }

                $count = count($clusterPixels);
                $centroids[$i] = [
                    $sumL / $count,
                    $sumA / $count,
                    $sumB / $count,
                ];
            }
        }

        return $centroids;
    }

    /**
     * Check if centroids have converged.
     *
     * @param array<array<float>> $oldCentroids
     * @param array<array<float>> $newCentroids
     */
    private function hasConverged(array $oldCentroids, array $newCentroids): bool
    {
        foreach ($oldCentroids as $index => $oldCentroid) {
            $distance = $this->euclideanDistance($oldCentroid, $newCentroids[$index]);
            if ($distance > self::CONVERGENCE_THRESHOLD) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate Euclidean distance in OKLab color space.
     *
     * @param array<float> $color1
     * @param array<float> $color2
     */
    private function euclideanDistance(array $color1, array $color2): float
    {
        $dl = $color1[0] - $color2[0];
        $da = $color1[1] - $color2[1];
        $db = $color1[2] - $color2[2];

        return sqrt($dl * $dl + $da * $da + $db * $db);
    }
}
