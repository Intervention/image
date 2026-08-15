<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

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
            return $this->quantizer($colors)->quantizeColors($colors)
                ->sortByPresenceDesc()
                ->slice(0, $this->limit);
        } catch (ColorException $e) {
            throw new AnalyzerException('Unable to analyze image colors', previous: $e);
        }
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
