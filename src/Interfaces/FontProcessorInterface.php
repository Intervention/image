<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Typography\TextBlock;

interface FontProcessorInterface
{
    /**
     * Calculate size of bounding box of given text in conjunction with the given font
     *
     * @throws FontException
     */
    public function boxSize(string $text, FontInterface $font): SizeInterface;

    /**
     * Build TextBlock object from text string and align every line according
     * to text modifier's font object and position.
     *
     * @throws FontException
     */
    public function textBlock(string $text, FontInterface $font, PointInterface $position): TextBlock;

    /**
     * Calculate the actual font size to pass at the driver level
     */
    public function nativeFontSize(FontInterface $font): float;

    /**
     * Calculate the typographical font size in pixels
     *
     * @throws FontException
     */
    public function typographicalSize(FontInterface $font): int;

    /**
     * Calculates typographical cap height
     *
     * @throws FontException
     */
    public function capHeight(FontInterface $font): int;

    /**
     * Calculates typographical leading size
     *
     * @throws FontException
     */
    public function leading(FontInterface $font): int;
}
