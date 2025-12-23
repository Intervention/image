<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;

class Color
{
    public static function rgb(mixed ...$input): RgbColor
    {
        return RgbColor::create(...$input);
    }

    public static function cmyk(mixed ...$input): RgbColor
    {
        return CmykColor::create(...$input);
    }

    public static function hsl(mixed ...$input): RgbColor
    {
        return HslColor::create(...$input);
    }

    public static function hsv(mixed ...$input): RgbColor
    {
        return HsvColor::create(...$input);
    }

    public static function oklab(mixed ...$input): RgbColor
    {
        return OklabColor::create(...$input);
    }
}
