<?php

namespace Intervention\Image\Colors\Hsv;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    public static $channels = [
        Channels\Hue::class,
        Channels\Saturation::class,
        Channels\Value::class
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     */
    public function colorFromNormalized(array $normalized): ColorInterface
    {
        $values = array_map(function ($classname, $value_normalized) {
            return (new $classname(normalized: $value_normalized))->value();
        }, self::$channels, $normalized);

        return new Color(...$values);
    }

    public function convertColor(ColorInterface $color): ColorInterface
    {
        return match (get_class($color)) {
            CmykColor::class => $this->convertRgbColor($color->convertTo(RgbColorspace::class)),
            RgbColor::class => $this->convertRgbColor($color),
            default => $color,
        };
    }

    protected function convertRgbColor(RgbColor $color): ColorInterface
    {
        // percentage values of rgb channels
        $values = array_map(function ($channel) {
            return $channel->normalize();
        }, $color->channels());

        // take only RGB
        $values = array_slice($values, 0, 3);

        // calculate chroma
        $min = min(...$values);
        $max = max(...$values);
        $chroma = $max - $min;

        // calculate value
        $v = 100 * $max;

        // calculate saturation
        $s = 100 * ($chroma / $max);

        // calculate hue
        list($r, $g, $b) = $values;
        $h = match (true) {
            ($r == $min) => 3 - (($g - $b) / $chroma),
            ($b == $min) => 1 - (($r - $g) / $chroma),
            default => 5 - (($b - $r) / $chroma),
        } * 60;

        return new Color(
            intval(round($h)),
            intval(round($s)),
            intval(round($v))
        );
    }
}
