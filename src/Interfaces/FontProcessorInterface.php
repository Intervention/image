<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Typography\TextBlock;

interface FontProcessorInterface
{
    /**
     * Calculate size of bounding box of given text in conjuction with the given font
     *
     * @return SizeInterface
     */
    public function boxSize(string $text, FontInterface $font): SizeInterface;

    /**
     * Build TextBlock object from text string and align every line according
     * to text modifier's font object and position.
     *
     * @param string $text
     * @param FontInterface $font
     * @param PointInterface $position
     * @return TextBlock
     */
    public function textBlock(string $text, FontInterface $font, PointInterface $position): TextBlock;

    /**
     * Calculate the actual font size to pass at the driver level
     *
     * @return float
     */
    public function nativeFontSize(FontInterface $font): float;

    /**
     * Calculate the typographical font size in pixels
     *
     * @return int
     */
    public function typographicalSize(FontInterface $font): int;

    /**
     * Calculates typographical cap height
     *
     * @param FontInterface $font
     * @return int
     */
    public function capHeight(FontInterface $font): int;

    /**
     * Calculates typographical leading size
     *
     * @param FontInterface $font
     * @return int
     */
    public function leading(FontInterface $font): int;
}
