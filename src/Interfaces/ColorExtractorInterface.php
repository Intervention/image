<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorExtractorInterface
{
    /**
     * Extract the colors that appear most frequently in the image, sorted by popularity (highest first).
     */
    public function popular(int $limit = 256): PaletteInterface;

    /**
     * Extract the visually dominant colors in the image, starting with the most dominant ones.
     */
    public function dominant(int $limit = 8): PaletteInterface;

    /**
     * Extract categorized color swatches.
     */
    public function swatches(): SwatchesInterface;
}
