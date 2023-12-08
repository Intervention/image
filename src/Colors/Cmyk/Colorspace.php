<?php

namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    public static $channels = [
        Channels\Cyan::class,
        Channels\Magenta::class,
        Channels\Yellow::class,
        Channels\Key::class
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::createColor()
     */
    public function colorFromNormalized(array $normalized): ColorInterface
    {
        $values = array_map(function ($classname, $value_normalized) {
            return (new $classname(normalized: $value_normalized))->value();
        }, self::$channels, $normalized);

        return new Color(...$values);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::importColor()
     */
    public function importColor(ColorInterface $color): ColorInterface
    {
        return match (get_class($color)) {
            RgbColor::class => $this->importRgbColor($color),
            HsvColor::class => $this->importRgbColor($color->convertTo(RgbColorspace::class)),
            HslColor::class => $this->importRgbColor($color->convertTo(RgbColorspace::class)),
            default => $color,
        };
    }

    protected function importRgbColor(RgbColor $color): CmykColor
    {
        $c = (255 - $color->red()->value()) / 255.0 * 100;
        $m = (255 - $color->green()->value()) / 255.0 * 100;
        $y = (255 - $color->blue()->value()) / 255.0 * 100;
        $k = intval(round(min([$c, $m, $y])));

        $c = intval(round($c - $k));
        $m = intval(round($m - $k));
        $y = intval(round($y - $k));

        return new CmykColor($c, $m, $y, $k);
    }
}
