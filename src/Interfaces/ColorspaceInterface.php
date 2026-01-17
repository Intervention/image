<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorspaceInterface
{
    /**
     * Return array of all color channel classnames of the colorspace.
     *
     * @return array<string>
     */
    public static function channels(): array;

    /**
     * Create new color in colorspace from given normalized (0-1) channel values.
     *
     * @param array<float> $normalized
     */
    public static function colorFromNormalized(array $normalized): ColorInterface;

    /**
     * Convert given color to the format of the current colorspace.
     */
    public function importColor(ColorInterface $color): ColorInterface;
}
