<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Generator;
use Intervention\Image\Colors\Quantizer;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ColorPaletteAnalyzer implements AnalyzerInterface
{
    /**
     * @return array<ColorInterface>
     */
    public function analyze(ImageInterface $image): array // TODO: maybe generator
    {
        $colors = [];
        $quantizer = $this->quantizer($image);
        $colorspace = $image->colorspace();

        foreach ($this->sampleCoordinates($image->size()) as $coordinate) {
            $color = $image->colorAt(...$coordinate);

            if ($color->isClear()) {
                continue; // TODO: count clear as a single color
            }

            // normalize channel values, quantize and join to bin index
            $bin = implode(',', array_map(
                fn(float $value) => $quantizer->quantize($value),
                $this->normalizedChannelValues($color),
            ));

            if (!array_key_exists($bin, $colors)) {
                $colors[$bin] = 0;
            }

            $colors[$bin]++;
        }

        // sort most used first
        arsort($colors, SORT_REGULAR);
        $colors = array_keys($colors);

        // split bin index, dequantize and transform to color object
        return array_map(function (string $bin) use ($colorspace, $quantizer) {
            return $colorspace::colorFromNormalized(
                array_map(fn(int $value) => $quantizer->dequantize($value), explode(',', $bin)),
            );
        }, $colors);
    }

    /**
     * @return array<float>
     */
    protected function normalizedChannelValues(ColorInterface $color): array
    {
        return array_map(
            fn(ColorChannelInterface $channel) => $channel->normalized(),
            $color->channels(),
        );
    }

    /**
     * @param array<float> $normalized
     * @return array<int>
     */
    protected function quantizeNormalizedValues(array $normalized): array
    {
        $quantizer = new Quantizer();

        return array_map(
            fn(float $value) => $quantizer->quantize($value),
            $normalized,
        );
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
            default => 15, // > 500k: every 15th pixel
        };

        for ($y = 0; $y < $height; $y += $sampleRate) {
            for ($x = 0; $x < $width; $x += $sampleRate) {
                yield ['x' => $x, 'y' => $y];
            }
        }
    }

    private function quantizer(ImageInterface $image): Quantizer
    {
        $colorCount = $image->analyze(new ColorCountAnalyzer());

        return new Quantizer(levels: $colorCount <= 256 ? 256 : 8);
    }
}
