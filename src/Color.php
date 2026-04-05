<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
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
use Intervention\Image\Colors\Rgb\Channels\Alpha as RgbAlpha;
use Intervention\Image\Colors\Cmyk\Channels\Alpha as CmykAlpha;
use Intervention\Image\Colors\Hsl\Channels\Alpha as HslAlpha;
use Intervention\Image\Colors\Hsl\Channels\Hue as HslHue;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Colors\Hsl\Channels\Saturation as HslSaturation;
use Intervention\Image\Colors\Hsv\Channels\Alpha as HsvAlpha;
use Intervention\Image\Colors\Hsv\Channels\Hue;
use Intervention\Image\Colors\Hsv\Channels\Saturation;
use Intervention\Image\Colors\Hsv\Channels\Value;
use Intervention\Image\Colors\Oklab\Channels\A;
use Intervention\Image\Colors\Oklab\Channels\Alpha as OklabAlpha;
use Intervention\Image\Colors\Oklab\Channels\B;
use Intervention\Image\Colors\Oklab\Channels\Lightness as OklabLightness;
use Intervention\Image\Colors\Oklch\Channels\Alpha as OklchAlpha;
use Intervention\Image\Colors\Oklch\Channels\Chroma;
use Intervention\Image\Colors\Oklch\Channels\Lightness as OklchLightness;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder as RgbHexColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\NamedColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder as RgbStringColorDecoder;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\NotSupportedException;

class Color
{
    /**
     * Parse color from string value.
     *
     * @throws InvalidArgumentException
     * @throws ColorException
     */
    public static function parse(string $input): ColorInterface
    {
        try {
            $color = InputHandler::usingDecoders([
                RgbStringColorDecoder::class,
                CmykStringColorDecoder::class,
                HsvStringColorDecoder::class,
                HslStringColorDecoder::class,
                OklabStringColorDecoder::class,
                OklchStringColorDecoder::class,
                NamedColorDecoder::class,
                RgbHexColorDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException | DriverException $e) {
            throw new InvalidArgumentException(
                'Unable to parse RGB color from input "' . $input . '"',
                previous: $e,
            );
        }

        if (!$color instanceof ColorInterface) {
            throw new ColorException('Result must be instance of ' . self::class . ', got ' . $color::class);
        }

        return $color;
    }

    /**
     * Create new RGB color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function rgb(int|Red $r, int|Green $g, int|Blue $b, float|RgbAlpha $a = 1): RgbColor
    {
        return new RgbColor($r, $g, $b, $a);
    }

    /**
     * Create new CMYK color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function cmyk(
        int|Cyan $c,
        int|Magenta $m,
        int|Yellow $y,
        int|Key $k,
        float|CmykAlpha $a = 1,
    ): CmykColor {
        return new CmykColor($c, $m, $y, $k, $a);
    }

    /**
     * Create new HSL color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function hsl(int|HslHue $h, int|HslSaturation $s, int|Luminance $l, float|HslAlpha $a = 1): HslColor
    {
        return new HslColor($h, $s, $l, $a);
    }

    /**
     * Create new HSV color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function hsv(int|Hue $h, int|Saturation $s, int|Value $v, float|HsvAlpha $a = 1): HsvColor
    {
        return new HsvColor($h, $s, $v, $a);
    }

    /**
     * Create new OKLAB color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function oklab(
        float|OklabLightness $l,
        float|A $a,
        float|B $b,
        float|OklabAlpha $alpha = 1,
    ): OklabColor {
        return new OklabColor($l, $a, $b, $alpha);
    }

    /**
     * Create new OKLCH color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function oklch(
        float|OklchLightness $l,
        float|Chroma $c,
        float|Hue $h,
        float|OklchAlpha $a = 1,
    ): OklchColor {
        return new OklchColor($l, $c, $h, $a);
    }

    /**
     * Create transparent RGB color.
     */
    public static function transparent(): ColorInterface
    {
        // @phpstan-ignore missingType.checkedException
        return new RgbColor(255, 255, 255, 0);
    }
}
