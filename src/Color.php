<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Colors\Cmyk;
use Intervention\Image\Colors\Hsl;
use Intervention\Image\Colors\Hsv;
use Intervention\Image\Colors\Oklab;
use Intervention\Image\Colors\Oklch;
use Intervention\Image\Colors\Rgb;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;

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
                Rgb\Decoders\StringColorDecoder::class,
                Cmyk\Decoders\StringColorDecoder::class,
                Hsv\Decoders\StringColorDecoder::class,
                Hsl\Decoders\StringColorDecoder::class,
                Oklab\Decoders\StringColorDecoder::class,
                Oklch\Decoders\StringColorDecoder::class,
                Rgb\Decoders\NamedColorDecoder::class,
                Rgb\Decoders\HexColorDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException | DriverException $e) {
            throw new InvalidArgumentException(
                'Unable to parse color from input "' . $input . '"',
                previous: $e,
            );
        }

        if (!$color instanceof ColorInterface) {
            throw new ColorException('Result must be instance of ' . ColorInterface::class . ', got ' . $color::class);
        }

        return $color;
    }

    /**
     * Create new RGB color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function rgb(
        int|Rgb\Channels\Red $r,
        int|Rgb\Channels\Green $g,
        int|Rgb\Channels\Blue $b,
        float|Rgb\Channels\Alpha $a = 1,
    ): Rgb\Color {
        return new Rgb\Color($r, $g, $b, $a);
    }

    /**
     * Create new CMYK color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function cmyk(
        int|Cmyk\Channels\Cyan $c,
        int|Cmyk\Channels\Magenta $m,
        int|Cmyk\Channels\Yellow $y,
        int|Cmyk\Channels\Key $k,
        float|Cmyk\Channels\Alpha $a = 1,
    ): Cmyk\Color {
        return new Cmyk\Color($c, $m, $y, $k, $a);
    }

    /**
     * Create new HSL color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function hsl(
        int|Hsl\Channels\Hue $h,
        int|Hsl\Channels\Saturation $s,
        int|Hsl\Channels\Luminance $l,
        float|Hsl\Channels\Alpha $a = 1,
    ): Hsl\Color {
        return new Hsl\Color($h, $s, $l, $a);
    }

    /**
     * Create new HSV color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function hsv(
        int|Hsv\Channels\Hue $h,
        int|Hsv\Channels\Saturation $s,
        int|Hsv\Channels\Value $v,
        float|Hsv\Channels\Alpha $a = 1,
    ): Hsv\Color {
        return new Hsv\Color($h, $s, $v, $a);
    }

    /**
     * Create new OKLAB color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function oklab(
        float|Oklab\Channels\Lightness $l,
        float|Oklab\Channels\A $a,
        float|Oklab\Channels\B $b,
        float|Oklab\Channels\Alpha $alpha = 1,
    ): Oklab\Color {
        return new Oklab\Color($l, $a, $b, $alpha);
    }

    /**
     * Create new OKLCH color.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    public static function oklch(
        float|Oklch\Channels\Lightness $l,
        float|Oklch\Channels\Chroma $c,
        float|Oklch\Channels\Hue $h,
        float|Oklch\Channels\Alpha $a = 1,
    ): Oklch\Color {
        return new Oklch\Color($l, $c, $h, $a);
    }

    /**
     * Create transparent RGB color.
     */
    public static function transparent(): ColorInterface
    {
        // @phpstan-ignore missingType.checkedException
        return new Rgb\Color(255, 255, 255, 0);
    }
}
