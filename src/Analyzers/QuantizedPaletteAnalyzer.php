<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Generator;
use Intervention\Image\Colors\RatedColor;
use Intervention\Image\Colors\Quantizer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class QuantizedPaletteAnalyzer extends AbstractPaletteAnalyzer implements AnalyzerInterface
{
    /**
     * Create new instance.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(protected int $limit = 256)
    {
        if ($this->limit < 1) {
            throw new InvalidArgumentException('Invalid $limit value. Must be int<1, max>');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     *
     * @throws AnalyzerException
     * @return array<RatedColor>
     */
    public function analyze(ImageInterface $image): array
    {
        try {
            $colors = $this->quantizeColors(
                $this->collectColors($image),
                $this->quantizationLevel($image),
            );
        } catch (InvalidArgumentException $e) {
            throw new AnalyzerException('Failed to analyze image pixels', previous: $e);
        }

        // sort by rating desc
        uasort($colors, fn(RatedColor $a, RatedColor $b): int => $b->rating <=> $a->rating);

        return array_slice($colors, 0, $this->limit);
    }

    /**
     * Quantize pixel colors.
     *
     * @param Generator<ColorInterface> $colors
     * @throws InvalidArgumentException
     * @throws AnalyzerException
     * @return array<RatedColor>
     */
    private function quantizeColors(Generator $colors, int $quantizationLevel = 8): array
    {
        $pixelMap = [];
        $quantizer = new Quantizer($quantizationLevel);

        foreach ($colors as $color) {
            try {
                $color = new RatedColor($quantizer->quantizeColor($color));
            } catch (ColorException $e) {
                throw new AnalyzerException('Failed to quantize color', previous: $e);
            }
            $key = $color->hash();

            if (!isset($pixelMap[$key])) {
                $pixelMap[$key] = $color;
            }

            $pixelMap[$key]->increaseRating();
        }

        return $pixelMap;
    }

    /**
     * Determine quantization level according to color count of given image and limit of analyzer.
     */
    private function quantizationLevel(ImageInterface $image): int
    {
        // 256 -> 1530
        // 128 -> 1414
        // 64 -> 1048
        // 54 -> 946
        // 32 -> 685
        // 24 -> 541
        // 18 -> 403
        // 16 -> 343
        // 12 -> 242
        // 8 -> 134
        // 7 -> 102
        // 6 -> 85
        // 5 -> 54
        // 4 -> 35
        // 3 -> 21
        // 2 -> 7

        $colorCount = $image->analyze(new ColorCountAnalyzer());

        if ($colorCount !== null && $colorCount < 256) {
            return Quantizer::QUANTIZATION_LEVEL_MAX; // no quantization, slice to limit later
        }

        // quantization adapting to limit, slice to limit later
        return match (true) {
            $this->limit <= 16 => 4,
            $this->limit <= 32 => 6,
            $this->limit <= 64 => 8,
            $this->limit <= 128 => 16,
            $this->limit <= 256 => 18,
            $this->limit <= 515 => 32,
            $this->limit <= 1000 => 128,
            default => 256,
        };
    }
}
