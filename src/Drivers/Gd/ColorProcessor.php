<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class ColorProcessor implements ColorProcessorInterface
{
    public function __construct(protected ColorspaceInterface $colorspace = new Colorspace())
    {
    }

    public function colorToNative(ColorInterface $color): int
    {
        // convert color to colorspace
        $color = $color->convertTo($this->colorspace);

        // gd only supports rgb so the channels can be accessed directly
        $r = $color->channel(Red::class)->value();
        $g = $color->channel(Green::class)->value();
        $b = $color->channel(Blue::class)->value();
        $a = $color->channel(Alpha::class)->value();

        // convert alpha value to gd alpha
        // ([opaque]255-0[transparent]) to ([opaque]0-127[transparent])
        $a = (int) $this->convertRange($a, 0, 255, 127, 0);

        return ($a << 24) + ($r << 16) + ($g << 8) + $b;
    }

    public function nativeToColor(mixed $value): ColorInterface
    {
        if (!is_int($value)) {
            throw new ColorException('GD driver can only decode colors in integer format.');
        }

        $a = ($value >> 24) & 0xFF;
        $r = ($value >> 16) & 0xFF;
        $g = ($value >> 8) & 0xFF;
        $b = $value & 0xFF;

        // convert gd apha integer to intervention alpha integer
        // ([opaque]0-127[transparent]) to ([opaque]255-0[transparent])
        $a = (int) static::convertRange($a, 127, 0, 0, 255);

        return new Color($r, $g, $b, $a);
    }

    /**
     * Convert input in range (min) to (max) to the corresponding value
     * in target range (targetMin) to (targetMax).
     *
     * @param float|int $input
     * @param float|int $min
     * @param float|int $max
     * @param float|int $targetMin
     * @param float|int $targetMax
     * @return float|int
     */
    protected function convertRange(
        float|int $input,
        float|int $min,
        float|int $max,
        float|int $targetMin,
        float|int $targetMax
    ): float|int {
        return ceil(((($input - $min) * ($targetMax - $targetMin)) / ($max - $min)) + $targetMin);
    }
}
