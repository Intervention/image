<?php

namespace Intervention\Image\Drivers\Gd\Traits;

use GdImage;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;

trait DELETE___CanHandleColors
{
    /**
     * Allocate given color in given gd image and return color value/index
     *
     * @param GdImage $gd
     * @param ColorInterface $color
     * @return int
     */
    protected function allocateColor(GdImage $gd, ColorInterface $color): int
    {
        return imagecolorallocatealpha(
            $gd,
            $color->channel(Red::class)->value(),
            $color->channel(Green::class)->value(),
            $color->channel(Blue::class)->value(),
            $this->convertRange($color->channel(Alpha::class)->value(), 0, 255, 127, 0)
        );
    }

    /**
     * Transforms array result from imagecolorsforindex() to Color object
     *
     * @param array $values
     * @return Color
     */
    protected function arrayToColor(array $values): Color
    {
        list($r, $g, $b, $a) = array_values($values);

        // convert gd apha integer to intervention alpha integer
        // ([opaque]0-127[transparent]) to ([opaque]255-0[transparent])
        $a = (int) static::convertRange($a, 127, 0, 0, 255);

        return new Color($r, $g, $b, $a);
    }

    /**
     * Transforms GD Library integer color value to RGB color object
     *
     * @param int $value
     * @return Color
     */
    public function integerToColor(int $value): Color
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
     * @param Color $color
     * @return int
     */
    public function colorToInteger(Color $color): int
    {
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
    protected static function convertRange(
        float|int $input,
        float|int $min,
        float|int $max,
        float|int $targetMin,
        float|int $targetMax
    ): float|int {
        return ceil(((($input - $min) * ($targetMax - $targetMin)) / ($max - $min)) + $targetMin);
    }
}
