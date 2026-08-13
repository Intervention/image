<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Colors\Histogram;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Random\Engine\Mt19937;
use Random\Randomizer;

class DominantPaletteAnalyzer extends AbstractPaletteAnalyzer implements AnalyzerInterface
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
     * Local RNG instance (does not affect global mt_rand state).
     */
    private Randomizer $rng;

    /**
     * Create new instance.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(protected int $limit = 8)
    {
        if ($this->limit < 1) {
            throw new InvalidArgumentException('Invalid $limit value. Must be int<1, max>');
        }

        // use local RNG to avoid corrupting global mt_rand state
        $this->rng = new Randomizer(new Mt19937(self::SEED));
    }

    /**
     * Analyze dominant colors in given image.
     *
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): Histogram
    {
        // get pixels
        $pixels = $this->collectColors($image);

        // convert to oklab
        $pixels = array_map(
            fn(ColorInterface $color): ColorInterface => $color->toColorspace(Oklab::class),
            iterator_to_array($pixels),
        );

        // @phpstan-ignore argument.type
        $clusters = $this->kMeansClustering($pixels); // perform K-means clustering

        // convert centroids back to original colorspace
        $histogram = new Histogram();
        $totalPixels = count($pixels);
        $minClusterSize = ($totalPixels * self::MIN_CLUSTER_SIZE_PERCENT) / 100;
        $colorspace = $image->colorspace();

        foreach ($clusters as $cluster) {
            // filter out very small clusters
            if ($cluster['size'] < $minClusterSize) {
                continue;
            }

            try {
                $histogram->addColor(
                    $cluster['centroid']->toColorspace($colorspace),
                    $cluster['size'],
                );
            } catch (InvalidArgumentException $e) {
                throw new AnalyzerException('');
            }
        }

        return $histogram;
    }

    /**
     * Perform K-means clustering on Oklab pixel data.
     *
     * @param array<OklabColor> $pixels
     * @throws AnalyzerException
     * @return array<array{centroid: OklabColor, size: int}>
     */
    private function kMeansClustering(array $pixels): array
    {
        $k = min($this->limit, count($pixels));

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
     * @param array<OklabColor> $pixels
     * @throws AnalyzerException
     * @return array<OklabColor>
     */
    private function initializeCentroids(array $pixels, int $k): array
    {
        $centroids = [];

        if (count($pixels) === 0) {
            return $centroids;
        }

        $pixelIndices = array_keys($pixels);

        // choose first centroid randomly (deterministic with seed)
        $firstIndex = $pixelIndices[$this->rng->getInt(0, count($pixelIndices) - 1)];
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
            $target = $this->rng->getFloat(0, $sumDistances);

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
     * @param array<OklabColor> $pixels
     * @param array<OklabColor> $centroids
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
     * @param array<OklabColor> $pixels
     * @param array<int> $assignments
     * @throws AnalyzerException
     * @return array<OklabColor>
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
                $randomIndex = $this->rng->getInt(0, count($pixels) - 1);
                $centroids[$i] = $pixels[$randomIndex];
            } else {
                // calculate mean of all pixels in cluster
                $sumL = 0.0;
                $sumA = 0.0;
                $sumB = 0.0;

                foreach ($clusterPixels as $pixel) {
                    $sumL += $pixel->lightness()->value();
                    $sumA += $pixel->a()->value();
                    $sumB += $pixel->b()->value();
                }

                $count = count($clusterPixels);
                try {
                    $centroids[$i] = new OklabColor(
                        $sumL / $count,
                        $sumA / $count,
                        $sumB / $count,
                    );
                } catch (InvalidArgumentException $e) {
                    throw new AnalyzerException('Failed to update centroids', previous: $e);
                }
            }
        }

        return $centroids;
    }

    /**
     * Check if centroids have converged.
     *
     * @param array<OklabColor> $oldCentroids
     * @param array<OklabColor> $newCentroids
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
     * Calculate Euclidean distance in Oklab color space.
     */
    private function euclideanDistance(OklabColor $color1, OklabColor $color2): float
    {
        $dl = $color1->lightness()->value() - $color2->lightness()->value();
        $da = $color1->a()->value() - $color2->a()->value();
        $db = $color1->b()->value() - $color2->b()->value();

        return sqrt($dl * $dl + $da * $da + $db * $db);
    }
}
