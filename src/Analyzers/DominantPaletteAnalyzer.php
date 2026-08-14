<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

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
     * Local RNG instance.
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

        // use local RNG to avoid corrupting global mt_rand state
        $this->rng = new Randomizer(new Mt19937(self::SEED));
    }

    /**
     * Analyze dominant colors in given image.
     *
     * @throws InvalidArgumentException
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): PaletteInterface
    {
        $colors = $this->collectColors($image, $this->region);

        // convert to oklab
        $colors = array_map(
            fn(ColorInterface $color): ColorInterface => $color->toColorspace(Oklab::class),
            iterator_to_array($colors),
        );

        // @phpstan-ignore argument.type
        $clusters = $this->kMeansClustering($colors); // perform K-means clustering

        $palette = new Palette();
        $totalColors = count($colors);
        $minClusterSize = ($totalColors * self::MIN_CLUSTER_SIZE_PERCENT) / 100;
        $colorspace = $image->colorspace();

        foreach ($clusters as $cluster) {
            // filter out very small clusters
            if ($cluster['size'] < $minClusterSize) {
                continue;
            }

            try {
                $palette->addColor(
                    $cluster['centroid']->toColorspace($colorspace), // convert centroids back to original colorspace
                    $cluster['size'],
                );
            } catch (InvalidArgumentException $e) {
                throw new AnalyzerException('Unable to analyze image colors', previous: $e);
            }
        }

        return $palette;
    }

    /**
     * Perform K-means clustering on Oklab color data.
     *
     * @param array<OklabColor> $colors
     * @throws AnalyzerException
     * @return array<array{centroid: OklabColor, size: int}>
     */
    private function kMeansClustering(array $colors): array
    {
        $k = min($this->limit, count($colors));

        // initialize centroids using K-means++
        $centroids = $this->initializeCentroids($colors, $k);

        // update k to actual number of centroids found (may be less due to early termination)
        $k = count($centroids);
        $assignments = [];

        // iteratively refine clusters
        for ($iteration = 0; $iteration < self::MAX_ITERATIONS; $iteration++) {
            // assign colors to nearest centroid
            $assignments = $this->assignClusters($colors, $centroids);

            // calculate new centroids
            $newCentroids = $this->updateCentroids($colors, $assignments, $k);

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
     * @param array<OklabColor> $colors
     * @throws AnalyzerException
     * @return array<OklabColor>
     */
    private function initializeCentroids(array $colors, int $k): array
    {
        $centroids = [];

        if (count($colors) === 0) {
            return $centroids;
        }

        $colorIndices = array_keys($colors);

        // choose first centroid randomly (deterministic with seed)
        $firstIndex = $colorIndices[$this->rng->getInt(0, count($colorIndices) - 1)];
        $centroids[] = $colors[$firstIndex];

        // choose remaining centroids with probability proportional to distance from existing centroids
        for ($i = 1; $i < $k; $i++) {
            $distances = [];
            $sumDistances = 0.0;

            foreach ($colors as $index => $color) {
                // find minimum distance to any existing centroid
                $minDistance = PHP_FLOAT_MAX;
                foreach ($centroids as $centroid) {
                    $distance = $this->euclideanDistance($color, $centroid);
                    $minDistance = min($minDistance, $distance);
                }

                $distances[$index] = $minDistance * $minDistance; // square for better spread
                $sumDistances += $distances[$index];
            }

            // if all distances are zero, all unique colors have been found
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

            $centroids[] = $colors[$chosenIndex];
        }

        return $centroids;
    }

    /**
     * Assign each color to the nearest centroid.
     *
     * @param array<OklabColor> $colors
     * @param array<OklabColor> $centroids
     * @return array<int>
     */
    private function assignClusters(array $colors, array $centroids): array
    {
        $assignments = [];

        foreach ($colors as $color) {
            $minDistance = PHP_FLOAT_MAX;
            $closestCluster = 0;

            foreach ($centroids as $index => $centroid) {
                $distance = $this->euclideanDistance($color, $centroid);
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
     * @param array<OklabColor> $colors
     * @param array<int> $assignments
     * @throws AnalyzerException
     * @return array<OklabColor>
     */
    private function updateCentroids(array $colors, array $assignments, int $k): array
    {
        $centroids = [];

        for ($i = 0; $i < $k; $i++) {
            $clusterColors = [];

            foreach ($assignments as $colorIndex => $cluster) {
                if ($cluster === $i) {
                    $clusterColors[] = $colors[$colorIndex];
                }
            }

            if (count($clusterColors) === 0) {
                // if cluster is empty, reinitialize randomly
                $randomIndex = $this->rng->getInt(0, count($colors) - 1);
                $centroids[$i] = $colors[$randomIndex];
            } else {
                // calculate mean of all colors in cluster
                $sumL = 0.0;
                $sumA = 0.0;
                $sumB = 0.0;

                foreach ($clusterColors as $color) {
                    $sumL += $color->lightness()->value();
                    $sumA += $color->a()->value();
                    $sumB += $color->b()->value();
                }

                $count = count($clusterColors);
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
