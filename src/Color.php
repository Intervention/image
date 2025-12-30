<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;

class Color
{
    /**
     * Create new RGB color
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function rgb(mixed ...$input): RgbColor
    {
        return RgbColor::create(...$input);
    }

    /**
     * Create new CMYK color
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function cmyk(mixed ...$input): CmykColor
    {
        return CmykColor::create(...$input);
    }

    /**
     * Create new HSL color
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function hsl(mixed ...$input): HslColor
    {
        return HslColor::create(...$input);
    }

    /**
     * Create new HSV color
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function hsv(mixed ...$input): HsvColor
    {
        return HsvColor::create(...$input);
    }

    /**
     * Create new OKLAB color
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function oklab(mixed ...$input): OklabColor
    {
        return OklabColor::create(...$input);
    }

    /**
     * Create new OKLCH color
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function oklch(mixed ...$input): OklchColor
    {
        return OklchColor::create(...$input);
    }
}
