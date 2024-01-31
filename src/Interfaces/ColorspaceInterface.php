<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorspaceInterface
{
    /**
     * Convert given color to the format of the current colorspace
     *
     * @param ColorInterface $color
     * @return ColorInterface
     */
    public function importColor(ColorInterface $color): ColorInterface;

    /**
     * Create new color in colorspace from given normalized channel values
     *
     * @param array $normalized
     * @return ColorInterface
     */
    public function colorFromNormalized(array $normalized): ColorInterface;
}
