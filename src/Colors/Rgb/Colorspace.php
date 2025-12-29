<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use TypeError;

class Colorspace implements ColorspaceInterface
{
    /**
     * Channel class names of colorspace
     *
     * @var array<string>
     */
    public static array $channels = [
        Channels\Red::class,
        Channels\Green::class,
        Channels\Blue::class,
        Channels\Alpha::class
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     */
    public static function colorFromNormalized(array $normalized): ColorInterface
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

    public function importColor(ColorInterface $color): ColorInterface
    {
        return match ($color::class) {
            CmykColor::class => $this->importCmykColor($color),
            HsvColor::class => $this->importHsvColor($color),
            HslColor::class => $this->importHslColor($color),
            OklabColor::class => $this->importOklabColor($color),
            OklchColor::class => $this->importOklabColor($color->toColorspace(Oklab::class)),
            RgbColor::class => $color,
            default => throw new NotSupportedException(
                'Unable to import color ' . $color::class . ' to ' . $this::class,
            ),
        };
    }

    private function importCmykColor(CmykColor $color): RgbColor
    {
        return new Color(
            (int) (255 * (1 - $color->cyan()->normalize()) * (1 - $color->key()->normalize())),
            (int) (255 * (1 - $color->magenta()->normalize()) * (1 - $color->key()->normalize())),
            (int) (255 * (1 - $color->yellow()->normalize()) * (1 - $color->key()->normalize())),
        );
    }

    private function importHsvColor(HsvColor $color): RgbColor
    {
        $chroma = $color->value()->normalize() * $color->saturation()->normalize();
        $hue = $color->hue()->normalize() * 6;
        $x = $chroma * (1 - abs(fmod($hue, 2) - 1));

        // connect channel values
        $values = match (true) {
            $hue < 1 => [$chroma, $x, 0],
            $hue < 2 => [$x, $chroma, 0],
            $hue < 3 => [0, $chroma, $x],
            $hue < 4 => [0, $x, $chroma],
            $hue < 5 => [$x, 0, $chroma],
            default => [$chroma, 0, $x],
        };

        // add to each value
        $values = array_map(fn(float|int $value): float => $value + $color->value()->normalize() - $chroma, $values);
        $values[] = $color->alpha()->normalize(); // append alpha channel value

        return $this->colorFromNormalized($values);
    }

    private function importHslColor(HslColor $color): RgbColor
    {
        // normalized values of hsl channels
        [$h, $s, $l] = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalize(),
            $color->channels()
        );

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h * 6, 2) - 1));
        $m = $l - $c / 2;

        $values = match (true) {
            $h < 1 / 6 => [$c, $x, 0],
            $h < 2 / 6 => [$x, $c, 0],
            $h < 3 / 6 => [0, $c, $x],
            $h < 4 / 6 => [0, $x, $c],
            $h < 5 / 6 => [$x, 0, $c],
            default => [$c, 0, $x],
        };

        $values = array_map(fn(float|int $value): float => $value + $m, $values);
        $values[] = $color->alpha()->normalize(); // append alpha channel value

        return $this->colorFromNormalized($values);
    }

    private function importOklabColor(OklabColor $color): RgbColor
    {
        $linearToRgb = function (float $c): float {
            $c = max(0.0, min(1.0, $c));

            if ($c <= 0.0031308) {
                return 12.92 * $c;
            }

            return 1.055 * ($c ** (1 / 2.4)) - 0.055;
        };

        $l = $color->lightness()->value() + 0.3963377774 * $color->a()->value() + 0.2158037573 * $color->b()->value();
        $m = $color->lightness()->value() - 0.1055613458 * $color->a()->value() - 0.0638541728 * $color->b()->value();
        $s = $color->lightness()->value() - 0.0894841775 * $color->a()->value() - 1.2914855480 * $color->b()->value();

        $l = $l ** 3;
        $m = $m ** 3;
        $s = $s ** 3;

        $r = +4.0767416621 * $l - 3.3077115913 * $m + 0.2309699292 * $s;
        $g = -1.2684380046 * $l + 2.6097574011 * $m - 0.3413193965 * $s;
        $b = -0.0041960863 * $l - 0.7034186147 * $m + 1.7076147010 * $s;

        $r = $linearToRgb($r);
        $g = $linearToRgb($g);
        $b = $linearToRgb($b);

        return new Color(
            (int) round($r * 255),
            (int) round($g * 255),
            (int) round($b * 255),
            $color->alpha()->value(),
        );
    }
}
