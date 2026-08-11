<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Generator;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

abstract class AbstractPaletteAnalyzer implements AnalyzerInterface
{
    /**
     * Collect pixel colors from the image.
     *
     * @return Generator<ColorInterface>
     */
    protected function collectColors(ImageInterface $image): Generator
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
     * Get dynamic grid of pixel sample coordinates according to current image size.
     *
     * @return Generator<array{x: int, y: int}>
     */
    protected function sampleCoordinates(SizeInterface $size): Generator
    {
        $width = $size->width();
        $height = $size->height();
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
