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
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder as CmykStringColorDecoder;
use Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder as HslStringColorDecoder;
use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder as HsvStringColorDecoder;
use Intervention\Image\Colors\Oklab\Decoders\StringColorDecoder as OklabStringColorDecoder;
use Intervention\Image\Colors\Oklch\Decoders\StringColorDecoder as OklchStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder as RgbHexColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\NamedColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder as RgbStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\TransparentColorDecoder;
use Intervention\Image\Exceptions\NotSupportedException;

class Color
{
    /**
     * Parse color from string value.
     */
    public static function parse(string $input): ColorInterface
    {
        try {
            return InputHandler::usingDecoders([
                RgbStringColorDecoder::class,
                CmykStringColorDecoder::class,
                HsvStringColorDecoder::class,
                HslStringColorDecoder::class,
                OklabStringColorDecoder::class,
                OklchStringColorDecoder::class,
                NamedColorDecoder::class,
                RgbHexColorDecoder::class,
                TransparentColorDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException) {
            throw new NotSupportedException('Unable to parse color from input "' . $input . '"');
        }
    }

    /**
     * Create new RGB color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function rgb(mixed ...$input): RgbColor
    {
        return RgbColor::create(...$input);
    }

    /**
     * Create new CMYK color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function cmyk(mixed ...$input): CmykColor
    {
        return CmykColor::create(...$input);
    }

    /**
     * Create new HSL color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function hsl(mixed ...$input): HslColor
    {
        return HslColor::create(...$input);
    }

    /**
     * Create new HSV color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function hsv(mixed ...$input): HsvColor
    {
        return HsvColor::create(...$input);
    }

    /**
     * Create new OKLAB color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function oklab(mixed ...$input): OklabColor
    {
        return OklabColor::create(...$input);
    }

    /**
     * Create new OKLCH color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function oklch(mixed ...$input): OklchColor
    {
        return OklchColor::create(...$input);
    }
}
