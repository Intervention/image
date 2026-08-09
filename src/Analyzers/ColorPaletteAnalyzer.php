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
    public function analyze(ImageInterface $image): array
    {
        $pixels = $this->collectPixels($image);
        $pixels = $this->quantizePixels($pixels, $this->quantizationLevel($image));

        return array_map(fn(array $item): ColorInterface => $item['color'], $pixels);
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
     * @return array<array{color: ColorInterface, count: int}>
     */
    protected function quantizePixels(Generator $pixels, int $quantizationLevel = 8): array
    {
        $pixelMap = [];
        $quantizer = new Quantizer($quantizationLevel);
        foreach ($pixels as $color) {
            $color = $quantizer->quantizeColor($color);
            $key = $this->colorHash($color);

            if (!isset($pixelMap[$key])) {
                $pixelMap[$key] = ['color' => $color, 'count' => 0];
            }

            $pixelMap[$key]['count']++;
        }

        // sort by count desc
        usort($pixelMap, fn(array $a, array $b): int => $b['count'] <=> $a['count']);

        return $pixelMap;
    }

    /**
     * Normalize pixel colors.
     *
     * @return Generator<array<float>>
     */
    protected function normalizePixels(Generator $pixels): Generator
    {
        foreach ($pixels as $color) {
            yield array_map(
                fn(ColorChannelInterface $channel): float => $channel->normalized(),
                $color->channels(),
            );
        }
    }

    /**
     * Build unique hash from color.
     */
    protected function colorHash(ColorInterface $color): string
    {
        $channelValues = array_map(
            fn(ColorChannelInterface $channel): int|float => $channel->value(),
            $color->channels(),
        );

        return md5(implode(',', $channelValues));
    }

    /**
     * Determine quantization level according to color count of given image.
     */
    protected function quantizationLevel(ImageInterface $image): int
    {
        $colorCount = $image->analyze(new ColorCountAnalyzer());

        if ($colorCount === null) {
            return Quantizer::QUANTIZATION_LEVEL_DEFAULT;
        }

        return $colorCount < 256 ? Quantizer::QUANTIZATION_LEVEL_MAX : Quantizer::QUANTIZATION_LEVEL_DEFAULT;
    }
}
