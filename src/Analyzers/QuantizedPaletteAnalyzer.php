<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Generator;
use Intervention\Image\Colors\QuantizedColor;
use Intervention\Image\Colors\Quantizer;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class QuantizedPaletteAnalyzer implements AnalyzerInterface
{
    /**
     * Create instance with quantization level ranging from 1
     * to 256 (best quality) or null for auto detection.
     */
    public function __construct(protected ?int $quantizationLevel = null)
    {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     *
     * @return array<QuantizedColor>
     */
    public function analyze(ImageInterface $image): array
    {
        $pixels = $this->collectPixels($image);

        return $this->quantizePixels($pixels, $this->quantizationLevel($image));
    }

    /**
     * Collect pixel data from the image.
     *
     * @return Generator<ColorInterface>
     */
    protected function collectPixels(ImageInterface $image): Generator
    {
        foreach ($this->sampleCoordinates($image->size()) as $coordinate) {
            $color = $image->colorAt(...$coordinate);
            if ($color->isClear()) {
                continue;
            }

            yield $color;
        }
    }

    /**
     * Quantize pixel colors.
     *
     * @return array<QuantizedColor>
     */
    protected function quantizePixels(Generator $pixels, int $quantizationLevel = 8): array
    {
        $pixelMap = [];
        $quantizer = new Quantizer($quantizationLevel);
        foreach ($pixels as $color) {
            $color = $quantizer->quantizeColor($color);
            $key = $color->hash();

            if (!isset($pixelMap[$key])) {
                $pixelMap[$key] = $color;
            }

            $pixelMap[$key]->increasePopulation();
        }

        // sort by population desc
        uasort($pixelMap, fn(QuantizedColor $a, QuantizedColor $b): int => $b->population <=> $a->population);

        return $pixelMap;
    }

    /**
     * Determine quantization level according to color count of given image.
     */
    protected function quantizationLevel(ImageInterface $image): int
    {
        if ($this->quantizationLevel !== null) {
            return $this->quantizationLevel;
        }

        $colorCount = $image->analyze(new ColorCountAnalyzer());

        if ($colorCount === null) {
            return Quantizer::QUANTIZATION_LEVEL_DEFAULT;
        }

        return $colorCount < 256 ? Quantizer::QUANTIZATION_LEVEL_MAX : Quantizer::QUANTIZATION_LEVEL_DEFAULT;
    }

    /**
     * Get dynamic grid of pixel sample coordinates according to current image size.
     *
     * @return Generator<array{x: int, y: int}>
     */
    protected function sampleCoordinates(SizeInterface $size): Generator
    {
        [$width, $height] = $size;
        $totalPixels = $width * $height;

        $sampleRate = match (true) {
            $totalPixels <= 10000 => 1, // <= 10k pixels: sample all
            $totalPixels <= 100000 => 5, // 10k-100k: every 5th pixel
            $totalPixels <= 500000 => 10, // 100k-500k: every 10th pixel
            $totalPixels <= 2000000 => 20, // 500k-2m: every 20th pixel
            default => 30, // > 2m: every 30th pixel
        };

        for ($y = 0; $y < $height; $y += $sampleRate) {
            for ($x = 0; $x < $width; $x += $sampleRate) {
                yield ['x' => $x, 'y' => $y];
            }
        }
    }
}
