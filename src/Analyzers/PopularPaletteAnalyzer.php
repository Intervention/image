<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Colors\Palette;
use Intervention\Image\Colors\Quantizer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanHashColor;

class PopularPaletteAnalyzer extends AbstractPaletteAnalyzer
{
    use CanHashColor;

    /**
     * Create new instance.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(protected int $limit = 256, protected ?SizeInterface $region = null)
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
     * @throws InvalidArgumentException
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): PaletteInterface
    {
        try {
            $colors = iterator_to_array($this->collectColors($image, $this->region));
            $quantizer = $this->quantizer($colors);

            $palette = new Palette();
            $representatives = [];

            foreach ($colors as $color) {
                // first color selected is ok but I dont like the code duplication from palette hashin with $representatives here
                //
                // objective: a way to add colors to palette saving them with a quantized
                // hash and keeping the original values
                //
                // maybe new Palette([], quantizationlevels: 16, keepOriginal: true);
                // maybe $palette->addWithHash($hash, $color); <- NOPE
                // maybe $palette->addSimilar($color, $count, $levels);
                //
                // maybe palette with all colors in array then:
                // $palette->quantize() or/and $palette->reduce()
                //
                // maybe implement quantize() to Color itself then:
                // $color->quantize()->hash();
                $quantizedHash = $this->hashColor($quantizer->quantizeColor($color));
                $representatives[$quantizedHash] ??= $color;
                $palette->addColor($representatives[$quantizedHash]);

            }
        } catch (ColorException $e) {
            throw new AnalyzerException('Unable to analyze image colors', previous: $e);
        }

        return $palette
            ->sortByPresenceDesc()
            ->slice(0, $this->limit);
    }

    /**
     * Build quantizer according to the sampled colors and current limit.
     *
     * @param array<ColorInterface> $colors
     */
    private function quantizer(array $colors): Quantizer
    {
        // if the sampled colors contain no more than 256 distinct values,
        // quantization is performed with the highest detail level; counting
        // the samples keeps the decision identical for all drivers
        $distinct = [];
        foreach ($colors as $color) {
            $distinct[$this->hashColor($color)] = true;
            if (count($distinct) > 256) {
                break;
            }
        }

        if (count($distinct) <= 256) {
            // @phpstan-ignore missingType.checkedException
            return new Quantizer(Quantizer::LEVEL_MAX);
        }

        // @phpstan-ignore missingType.checkedException
        return new Quantizer(match (true) {
            $this->limit <= 256 => 20,
            $this->limit <= 512 => 30,
            $this->limit <= 1024 => 60,
            default => 256,
        });
    }
}
