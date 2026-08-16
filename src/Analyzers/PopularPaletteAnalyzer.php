<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Colors\Palette;
use Intervention\Image\Exceptions\AnalyzerException;
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
        $colors = iterator_to_array($this->collectColors($image, $this->region));
        $popular = new Palette($colors);

        return $popular
            ->reduce($this->quantizationLevels($colors))
            ->sortByPresenceDesc()
            ->slice(0, $this->limit);
    }

    /**
     * Get quantization limit according to the sampled colors and current limit.
     *
     * If the sampled colors contain no more than 256 distinct values,
     * quantization is performed with the highest detail level; counting
     * the samples keeps the decision identical for all drivers.
     *
     * @param array<ColorInterface> $colors
     */
    private function quantizationLevels(array $colors): int
    {
        $distinct = [];
        foreach ($colors as $color) {
            $distinct[$this->hashColor($color)] = true;
            if (count($distinct) > 256) {
                break;
            }
        }

        if (count($distinct) <= 256) {
            return 256; // max. level
        }

        return match (true) {
            $this->limit <= 256 => 20,
            $this->limit <= 512 => 30,
            $this->limit <= 1024 => 60,
            default => 256,
        };
    }
}
