<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsl;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorChannelInterface;
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
        Channels\Hue::class,
        Channels\Saturation::class,
        Channels\Luminance::class
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     */
    public function colorFromNormalized(array $normalized): ColorInterface
    {
        return new Color(...array_map(
            fn(string $classname, float $value_normalized) => (new $classname(normalized: $value_normalized))->value(),
            self::$channels,
            $normalized
        ));
    }

    /**
     * @param ColorInterface $color
     * @throws ColorException
     * @return ColorInterface
     */
    public function importColor(ColorInterface $color): ColorInterface
    {
        return match ($color::class) {
            CmykColor::class => $this->importRgbColor($color->convertTo(RgbColorspace::class)),
            RgbColor::class => $this->importRgbColor($color),
            HsvColor::class => $this->importHsvColor($color),
            default => $color,
        };
    }

    /**
     * @param ColorInterface $color
     * @throws ColorException
     * @return ColorInterface
     */
    protected function importRgbColor(ColorInterface $color): ColorInterface
    {
        if (!($color instanceof RgbColor)) {
            throw new ColorException('Unabled to import color of type ' . $color::class . '.');
        }

        // normalized values of rgb channels
        $values = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalize(),
            $color->channels(),
        );

        // take only RGB
        $values = array_slice($values, 0, 3);

        // calculate Luminance
        $min = min(...$values);
        $max = max(...$values);
        $luminance = ($max + $min) / 2;
        $delta = $max - $min;

        // calculate saturation
        $saturation = match (true) {
            $delta == 0 => 0,
            default => $delta / (1 - abs(2 * $luminance - 1)),
        };

        // calculate hue
        [$r, $g, $b] = $values;
        $hue = match (true) {
            ($delta == 0) => 0,
            ($max == $r) => 60 * fmod((($g - $b) / $delta), 6),
            ($max == $g) => 60 * ((($b - $r) / $delta) + 2),
            ($max == $b) => 60 * ((($r - $g) / $delta) + 4),
            default => 0,
        };

        $hue = ($hue + 360) % 360; // normalize hue

        return new Color(
            intval(round($hue)),
            intval(round($saturation * 100)),
            intval(round($luminance * 100)),
        );
    }

    /**
     * @param ColorInterface $color
     * @throws ColorException
     * @return ColorInterface
     */
    protected function importHsvColor(ColorInterface $color): ColorInterface
    {
        if (!($color instanceof HsvColor)) {
            throw new ColorException('Unabled to import color of type ' . $color::class . '.');
        }

        // normalized values of hsv channels
        [$h, $s, $v] = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalize(),
            $color->channels(),
        );

        // calculate Luminance
        $luminance = (2 - $s) * $v / 2;

        // calculate Saturation
        $saturation = match (true) {
            $luminance == 0 => $s,
            $luminance == 1 => 0,
            $luminance < .5 => $s * $v / ($luminance * 2),
            default => $s * $v / (2 - $luminance * 2),
        };

        return new Color(
            intval(round($h * 360)),
            intval(round($saturation * 100)),
            intval(round($luminance * 100)),
        );
    }
}
