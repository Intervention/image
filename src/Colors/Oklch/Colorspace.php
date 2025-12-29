<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklch;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\NotSupportedException;
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
        Channels\Chroma::class,
        Channels\Hue::class
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     */
    public static function colorFromNormalized(array $normalized): ColorInterface
    {
        return new Color(...array_map(
            fn(string $classname, float $normalized) => $classname::fromNormalized($normalized)->value(),
            self::$channels,
            $normalized
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::importColor()
     */
    public function importColor(ColorInterface $color): ColorInterface
    {
        return match ($color::class) {
            CmykColor::class,
            HsvColor::class,
            HslColor::class => $color->toColorspace(Rgb::class)->toColorspace($this::class),
            OklabColor::class => $this->importOklabColor($color),
            RgbColor::class => $this->importOklabColor($color->toColorspace(Oklab::class)),
            OklchColor::class => $color,
            default => throw new NotSupportedException(
                'Unable to import color ' . $color::class . ' to ' . $this::class,
            ),
        };
    }

    private function importOklabColor(OklabColor $color): OklchColor
    {
        $a = $color->a()->value();
        $b = $color->b()->value();

        $c = sqrt($a * $a + $b * $b);
        $h = rad2deg(atan2($b, $a));
        $h = $h < 0 ? $h + 360 : $h;

        return new Color(
            $color->lightness()->value(),
            $c,
            $h,
            $color->alpha()->value()
        );
    }
}
