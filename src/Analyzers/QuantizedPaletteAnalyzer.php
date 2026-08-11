<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Colors\RatedColor;
use Intervention\Image\Colors\Quantizer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\AnalyzerInterface;
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
            $colors = $this->quantizer($image)->quantizeColors(
                iterator_to_array($this->collectColors($image)),
            );
        } catch (InvalidArgumentException $e) {
            throw new AnalyzerException('Failed to analyze image pixels', previous: $e);
        }

        // sort by rating desc
        uasort($colors, fn(RatedColor $a, RatedColor $b): int => $b->rating <=> $a->rating);

        return $colors;
    }

    /**
     * Build quantizer according to image and current limit.
     *
     * @throws InvalidArgumentException
     */
    private function quantizer(ImageInterface $image): Quantizer
    {
        $colorCount = $image->analyze(new ColorCountAnalyzer());

        if ($colorCount !== null && $colorCount < 256) {
            // no quantization, slice to limit later
            return new Quantizer(
                Quantizer::QUANTIZATION_LEVEL_MAX,
                $this->limit,
            );
        }

        // quantization adapting to limit, slice to limit later
        return Quantizer::usingColorLimit($this->limit);
    }
}
