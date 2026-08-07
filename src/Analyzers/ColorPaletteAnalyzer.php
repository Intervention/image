<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Generator;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ColorPaletteAnalyzer implements AnalyzerInterface
{
    /**
     * @return array<array<float>>
     */
    public function analyze(ImageInterface $image): array // TODO: maybe generator
    {
        $colors = [];

        foreach ($this->sampleCoordinates($image->size()) as $coordinate) {
            $color = $image->colorAt(...$coordinate);
            if ($color->isClear()) {
                continue;
            }

            $colors[] = $this->normalizeColor($color);
        }

        $colors = array_unique($colors, SORT_REGULAR);

        return $colors;
    }

    /**
     * @return array<float>
     */
    protected function normalizeColor(ColorInterface $color): array
    {
        return array_map(
            fn(ColorChannelInterface $channel) => $channel->normalized(),
            $color->channels(),
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
}
