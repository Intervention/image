<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Generator;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

abstract class AbstractPaletteAnalyzer implements AnalyzerInterface
{
    /**
     * Collect sample colors from the image dependant on the image size and the given region.
     *
     * @throws InvalidArgumentException
     * @return Generator<ColorInterface>
     */
    protected function collectColors(ImageInterface $image, ?SizeInterface $region = null): Generator
    {
        $pixelAnalyzer = $image->driver()->specializeAnalyzer(new PixelColorAnalyzer(0, 0));

        foreach ($this->sampleCoordinates($image->size(), $region) as $coordinate) {
            [$pixelAnalyzer->x, $pixelAnalyzer->y] = $coordinate;
            $color = $image->analyze($pixelAnalyzer);
            if ($color->isClear()) {
                continue;
            }

            yield $color;
        }
    }

    /**
     * Get dynamic grid of pixel sample coordinates in the given region of the image size.
     *
     * @throws InvalidArgumentException
     * @return Generator<array<int, int>>
     */
    protected function sampleCoordinates(SizeInterface $size, ?SizeInterface $region = null): Generator
    {
        $region = $region === null ? $size : $region;

        $startX = $region->pivot()->x();
        $startY = $region->pivot()->y();
        $width = $region->width();
        $height = $region->height();

        // validate the region including its position, otherwise offset
        // regions would sample coordinates outside of the image
        if (
            $startX < 0 || $startY < 0
            || $startX + $width > $size->width()
            || $startY + $height > $size->height()
        ) {
            throw new InvalidArgumentException('The region must fit within the actual image size');
        }

        $totalPixels = $width * $height;

        $sampleRate = match (true) {
            $totalPixels <= 10000 => 1, // <= 10k pixels: sample all
            $totalPixels <= 100000 => 5, // 10k-100k: every 5th pixel
            $totalPixels <= 500000 => 10, // 100k-500k: every 10th pixel
            $totalPixels <= 2000000 => 20, // 500k-2m: every 20th pixel
            default => 30, // > 2m: every 30th pixel
        };

        $endX = $startX + $width;
        $endY = $startY + $height;

        for ($y = $startY; $y < $endY; $y += $sampleRate) {
            for ($x = $startX; $x < $endX; $x += $sampleRate) {
                yield [$x, $y];
            }
        }
    }
}
