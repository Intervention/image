<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Colors\Histogram;
use Intervention\Image\Colors\Quantizer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
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
     * @throws ColorException
     */
    public function analyze(ImageInterface $image): Histogram
    {
        try {
            return $this->quantizer($image)->quantizeColors(
                iterator_to_array($this->collectColors($image)),
            )->slice(0, $this->limit);
        } catch (InvalidArgumentException $e) {
            throw new AnalyzerException('Failed to analyze image pixels', previous: $e);
        }
    }

    /**
     * Build quantizer according to image and current limit.
     *
     * @throws InvalidArgumentException
     */
    private function quantizer(ImageInterface $image): Quantizer
    {
        try {
            $colorCount = $image->analyze(new ColorCountAnalyzer());
        } catch (NotSupportedException) {
            $colorCount = null;
        }

        // if image has less or equal than 256 colors, quantization is performed with highest detail level
        if ($colorCount !== null && $colorCount <= 256) {
            return new Quantizer(Quantizer::LEVEL_MAX);
        }

        return new Quantizer(match (true) {
            $this->limit <= 256 => 20,
            $this->limit <= 512 => 30,
            $this->limit <= 1024 => 60,
            default => 256,
        });
    }
}
