<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklab;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use TypeError;

class Colorspace implements ColorspaceInterface
{
    /**
     * Channel class names of colorspace.
     *
     * @var array<string>
     */
    public static array $channels = [
        Channels\Lightness::class,
        Channels\A::class,
        Channels\B::class,
        Channels\Alpha::class,
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     *
     * @throws InvalidArgumentException
     */
    public static function colorFromNormalized(array $normalized): OklabColor
    {
        // add alpha value if missing
        $normalized = count($normalized) === 3 ? array_pad($normalized, 4, 1) : $normalized;

        return new Color(...array_map(
            function (string $channel, null|float $normalized) {
                try {
                    return $channel::fromNormalized($normalized)->value();
                } catch (TypeError $e) {
                    throw new InvalidArgumentException(
                        'Normalized color value must be in range 0 to 1',
                        previous: $e
                    );
                }
            },
            self::$channels,
            $normalized
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::importColor()
     *
     * @throws NotSupportedException
     * @throws ColorDecoderException
     */
    public function importColor(ColorInterface $color): OklabColor
    {
        return match ($color::class) {
            CmykColor::class,
            HsvColor::class,
            HslColor::class => $this->importViaRgbColor($color),
            RgbColor::class => $this->importRgbColor($color),
            OklchColor::class => $this->importOklchColor($color),
            OklabColor::class => $color,
            default => throw new NotSupportedException(
                'Unable to import color ' . $color::class . ' to ' . $this::class,
            ),
        };
    }

    /**
     * Import given RGB color OKLAB colorspace.
     */
    private function importRgbColor(RgbColor $color): OklabColor
    {
        $cbrt = fn(float $x): float => $x < 0 ? -abs($x) ** (1 / 3) : $x ** (1 / 3);
        $rgbToLinear = fn(float $x): float => $x <= 0.04045 ? $x / 12.92 : (($x + 0.055) / 1.055) ** 2.4;

        $r = $color->red()->normalizedValue();
        $g = $color->green()->normalizedValue();
        $b = $color->blue()->normalizedValue();

        $r = $rgbToLinear($r);
        $g = $rgbToLinear($g);
        $b = $rgbToLinear($b);

        $l = 0.4122214708 * $r + 0.5363325363 * $g + 0.0514459929 * $b;
        $m = 0.2119034982 * $r + 0.6806995451 * $g + 0.1073969566 * $b;
        $s = 0.0883024619 * $r + 0.2817188376 * $g + 0.6299787005 * $b;

        $l = $cbrt($l);
        $m = $cbrt($m);
        $s = $cbrt($s);

        return new Color(
            0.2104542553 * $l + 0.7936177850 * $m - 0.0040720468 * $s,
            1.9779984951 * $l - 2.4285922050 * $m + 0.4505937099 * $s,
            0.0259040371 * $l + 0.7827717662 * $m - 0.8086757660 * $s,
            $color->alpha()->value(),
        );
    }

    /**
     * Import given OKLCH color OKLAB colorspace.
     */
    private function importOklchColor(OklchColor $color): OklabColor
    {
        $hRad = deg2rad($color->hue()->value());

        return new Color(
            $color->lightness()->value(),
            $color->chroma()->value() * cos($hRad),
            $color->chroma()->value() * sin($hRad),
            $color->alpha()->value(),
        );
    }

    /**
     * Import given color to OKLAB color space by converting it to RGB first.
     *
     * @throws ColorDecoderException
     */
    private function importViaRgbColor(CmykColor|HslColor|HsvColor $color): OklabColor
    {
        try {
            $color = $color->toColorspace(Rgb::class)->toColorspace($this::class);
        } catch (InvalidArgumentException | NotSupportedException $e) {
            throw new ColorDecoderException(
                'Failed to transform color to OKLAB color space',
                previous: $e
            );
        }

        if (!$color instanceof OklabColor) {
            throw new ColorDecoderException(
                'Failed to transform color to OKLAB color space',
            );
        }

        return $color;
    }
}
