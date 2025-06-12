<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorspaceInterface
{
    /**
     * Convert given color to the format of the current colorspace
     */
    public function importColor(ColorInterface $color): ColorInterface;

    /**
     * Create new color in colorspace from given normalized channel values
     *
     * @param array<float> $normalized
     */
    public function colorFromNormalized(array $normalized): ColorInterface;
}
