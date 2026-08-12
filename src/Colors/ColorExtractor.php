<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Analyzers\DominantPaletteAnalyzer;
use Intervention\Image\Analyzers\QuantizedPaletteAnalyzer;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PaletteInterface;

class ColorExtractor
{
    /**
     * Create new instance.
     */
    public function __construct(protected ImageInterface $image)
    {
        //
    }

    /**
     * Extract the colors that appear most frequently in the image, sorted by popularity (highest first).
     *
     * @throws InvalidArgumentException
     */
    public function popular(int $limit = 256): PaletteInterface
    {
        return $this->extractColors(new QuantizedPaletteAnalyzer($limit))->toPalette();
    }

    /**
     * Extract the visually dominant colors in the image, starting with the most dominant ones.
     *
     * @throws InvalidArgumentException
     */
    public function dominant(int $limit = 8): PaletteInterface
    {
        return $this->extractColors(new DominantPaletteAnalyzer($limit))->toPalette();
    }

    /**
     * Extract categorized color swatches.
     *
     * @throws ColorException
     */
    public function swatches(Classifier $classifier = new Classifier()): Swatches
    {
        try {
            $histogram = $this->extractColors(new QuantizedPaletteAnalyzer(256 * 256 * 256));
        } catch (InvalidArgumentException) {
            throw new ColorException('Failed to extract color swatches');
        }

        return $classifier->swatches($histogram);
    }

    /**
     * Extract colors from current image.
     */
    private function extractColors(AnalyzerInterface $strategy): Histogram
    {
        return $this->image->analyze($strategy);
    }
}
