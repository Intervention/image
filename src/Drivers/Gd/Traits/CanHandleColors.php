<?php

namespace Intervention\Image\Drivers\Gd\Traits;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Interfaces\ColorInterface;

trait CanHandleColors
{
    /**
     * Transforms GD Library integer color value to RGB color object
     *
     * @param int $value
     * @return Color
     */
    public function colorFromInteger(int $value): Color
    {
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
     * Transforms given color to the corresponding GD Library integer value
     *
     * @param ColorInterface $color
     * @return int
     */
    public function colorToInteger(ColorInterface $color): int
    {
        $color = $color->toRgb();

        $r = $color->red()->value();
        $g = $color->green()->value();
        $b = $color->blue()->value();
        $a = $color->alpha()->value();

        // convert alpha value to gd alpha
        // ([opaque]255-0[transparent]) to ([opaque]0-127[transparent])
        $a = (int) static::convertRange($a, 0, 255, 127, 0);

        return ($a << 24) + ($r << 16) + ($g << 8) + $b;
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
    private static function convertRange(
        float|int $input,
        float|int $min,
        float|int $max,
        float|int $targetMin,
        float|int $targetMax
    ): float|int {
        return ceil(((($input - $min) * ($targetMax - $targetMin)) / ($max - $min)) + $targetMin);
    }
}
