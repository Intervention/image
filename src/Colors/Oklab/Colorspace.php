<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklab;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    /**
     * Channel class names of colorspace
     *
     * @var array<string>
     */
    public static array $channels = [
        Channels\Lightness::class,
        Channels\A::class,
        Channels\B::class
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     */
    public function colorFromNormalized(array $normalized): ColorInterface
    {
        return new Color(...array_map(
            fn(string $classname, float $normalized) => $classname::fromNormalized($normalized)->value(),
            self::$channels,
            $normalized
        ));
    }

    public function importColor(ColorInterface $color): ColorInterface
    {
        return match ($color::class) {
            CmykColor::class,
            HsvColor::class,
            HslColor::class => $color->convertTo(RgbColorspace::class)->convertTo($this::class),
            RgbColor::class => $this->importRgbColor($color),
            default => $color,
        };
    }

    private function importRgbColor(RgbColor $color): ColorInterface
    {
        $cbrt = fn(float $x): float => $x < 0 ? -abs($x) ** (1 / 3) : $x ** (1 / 3);
        $rgbToLinear = fn(float $x): float => $x <= 0.04045 ? $x / 12.92 : (($x + 0.055) / 1.055) ** 2.4;

        $r = $color->red()->normalize();
        $g = $color->green()->normalize();
        $b = $color->blue()->normalize();

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
        );
    }
}
