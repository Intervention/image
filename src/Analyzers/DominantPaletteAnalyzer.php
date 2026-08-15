<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Generator;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Colors\Palette;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Random\Engine\Mt19937;
use Random\Randomizer;

class DominantPaletteAnalyzer extends AbstractPaletteAnalyzer
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
     * Local RNG.
     */
    private Randomizer $rng;

    /**
     * Create new instance.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(protected int $limit = 8, protected ?SizeInterface $region = null)
    {
        if ($this->limit < 1) {
            throw new InvalidArgumentException('Invalid $limit value. Must be int<1, max>');
        }

        $this->randomize();
    }

    /**
     * Analyze dominant colors in given image.
     *
     * @throws InvalidArgumentException
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): PaletteInterface
    {
        // re-seed on every run so the result only depends on the image
        $this->randomize();

        $points = $this->clusterableColors($this->collectColors($image, $this->region));
        $clusters = $this->kMeansClustering($points); // perform K-means clustering

        $palette = new Palette();
        $minClusterSize = (count($points) * self::MIN_CLUSTER_SIZE_PERCENT) / 100;
        $colorspace = $image->colorspace();

        foreach ($clusters as $cluster) {
            // filter out very small clusters
            if ($cluster['size'] < $minClusterSize) {
                continue;
            }

            try {
                $palette->addColor(
                    // convert centroids back to original colorspace
                    (new OklabColor(...$cluster['centroid']))->toColorspace($colorspace),
                    $cluster['size'],
                );
            } catch (InvalidArgumentException $e) {
                throw new AnalyzerException('Unable to analyze image colors', previous: $e);
            }
        }

        // palette has already a limit of k and is already sorted by cluster size
        return $palette;
    }

    /**
     * Transform color to flattened oklab color channel triples which can be used for clustering.
     *
     * @param Generator<ColorInterface> $colors
     * @throws AnalyzerException
     * @return array<array{float, float, float}>
     */
    private function clusterableColors(Generator $colors): array
    {
        $clusterable = [];
        foreach ($colors as $color) {
            $oklab = $color->toColorspace(Oklab::class);
            if (!$oklab instanceof OklabColor) {
                throw new AnalyzerException('Unable to analyze image colors, failed to transform color space');
            }

            $clusterable[] = [
                $oklab->lightness()->value(),
                $oklab->a()->value(),
                $oklab->b()->value(),
            ];
        }

        return $clusterable;
    }

    /**
     * Perform K-means clustering on Oklab value triples.
     *
     * @param array<array{float, float, float}> $points
     * @return array<array{centroid: array{float, float, float}, size: int}>
     */
    private function kMeansClustering(array $points): array
    {
        $k = min($this->limit, count($points));

        // initialize centroids using K-means++
        $centroids = $this->initializeCentroids($points, $k);

        // update k to actual number of centroids found (may be less due to early termination)
        $k = count($centroids);
        $assignments = [];

        // iteratively refine clusters
        for ($iteration = 0; $iteration < self::MAX_ITERATIONS; $iteration++) {
            // assign points to nearest centroid
            $assignments = $this->assignClusters($points, $centroids);

            // calculate new centroids
            $newCentroids = $this->updateCentroids($points, $assignments, $k);

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
     * @param array<array{float, float, float}> $points
     * @return array<array{float, float, float}>
     */
    private function initializeCentroids(array $points, int $k): array
    {
        $centroids = [];

        if (count($points) === 0) {
            return $centroids;
        }

        // choose first centroid randomly (deterministic with seed)
        $centroids[] = $points[$this->rng->getInt(0, count($points) - 1)];

        // choose remaining centroids with probability proportional to distance from existing centroids
        for ($i = 1; $i < $k; $i++) {
            $distances = [];
            $sumDistances = 0.0;

            foreach ($points as $index => $point) {
                // find minimum squared distance to any existing centroid
                $minDistance = PHP_FLOAT_MAX;
                foreach ($centroids as $centroid) {
                    $distance = $this->squaredDistance($point, $centroid);
                    $minDistance = min($minDistance, $distance);
                }

                $distances[$index] = $minDistance; // squared for better spread
                $sumDistances += $minDistance;
            }

            // if all distances are zero, all unique points have been found
            if ($sumDistances === 0.0) {
                break;
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

            $centroids[] = $points[$chosenIndex];
        }

        return $centroids;
    }

    /**
     * Assign each point to the nearest centroid.
     *
     * @param array<array{float, float, float}> $points
     * @param array<array{float, float, float}> $centroids
     * @return array<int>
     */
    private function assignClusters(array $points, array $centroids): array
    {
        $assignments = [];

        foreach ($points as $point) {
            $minDistance = PHP_FLOAT_MAX;
            $closestCluster = 0;

            foreach ($centroids as $index => $centroid) {
                $distance = $this->squaredDistance($point, $centroid);
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
     * Accumulates per-cluster sums in a single pass over the assignments.
     *
     * @param array<array{float, float, float}> $points
     * @param array<int> $assignments
     * @return array<array{float, float, float}>
     */
    private function updateCentroids(array $points, array $assignments, int $k): array
    {
        $sums = array_fill(0, $k, [0.0, 0.0, 0.0]);
        $counts = array_fill(0, $k, 0);

        foreach ($assignments as $index => $cluster) {
            $sums[$cluster][0] += $points[$index][0];
            $sums[$cluster][1] += $points[$index][1];
            $sums[$cluster][2] += $points[$index][2];
            $counts[$cluster]++;
        }

        $centroids = [];

        for ($i = 0; $i < $k; $i++) {
            if ($counts[$i] === 0) {
                // if cluster is empty, reinitialize randomly
                $centroids[$i] = $points[$this->rng->getInt(0, count($points) - 1)];
            } else {
                // calculate mean of all points in cluster
                $centroids[$i] = [
                    $sums[$i][0] / $counts[$i],
                    $sums[$i][1] / $counts[$i],
                    $sums[$i][2] / $counts[$i],
                ];
            }
        }

        return $centroids;
    }

    /**
     * Check if centroids have converged.
     *
     * @param array<array{float, float, float}> $oldCentroids
     * @param array<array{float, float, float}> $newCentroids
     */
    private function hasConverged(array $oldCentroids, array $newCentroids): bool
    {
        foreach ($oldCentroids as $index => $oldCentroid) {
            $distance = $this->squaredDistance($oldCentroid, $newCentroids[$index]);
            if ($distance > self::CONVERGENCE_THRESHOLD ** 2) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate squared Euclidean distance between two Oklab value triples.
     *
     * @param array{float, float, float} $point1
     * @param array{float, float, float} $point2
     */
    private function squaredDistance(array $point1, array $point2): float
    {
        $dl = $point1[0] - $point2[0];
        $da = $point1[1] - $point2[1];
        $db = $point1[2] - $point2[2];

        return $dl * $dl + $da * $da + $db * $db;
    }

    /**
     * Re-seed local RNG.
     */
    private function randomize(): void
    {
        $this->rng = new Randomizer(new Mt19937(self::SEED));
    }
}
