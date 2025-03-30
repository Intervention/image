<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsv;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
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
        Channels\Value::class
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
            HslColor::class => $this->importHslColor($color),
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
        $values = array_map(fn(ColorChannelInterface $channel): float => $channel->normalize(), $color->channels());

        // take only RGB
        $values = array_slice($values, 0, 3);

        // calculate chroma
        $min = min(...$values);
        $max = max(...$values);
        $chroma = $max - $min;

        // calculate value
        $v = 100 * $max;

        if ($chroma == 0) {
            // greyscale color
            return new Color(0, 0, intval(round($v)));
        }

        // calculate saturation
        $s = 100 * ($chroma / $max);

        // calculate hue
        [$r, $g, $b] = $values;
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

    /**
     * @param ColorInterface $color
     * @throws ColorException
     * @return ColorInterface
     */
    protected function importHslColor(ColorInterface $color): ColorInterface
    {
        if (!($color instanceof HslColor)) {
            throw new ColorException('Unabled to import color of type ' . $color::class . '.');
        }

        // normalized values of hsl channels
        [$h, $s, $l] = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalize(),
            $color->channels()
        );

        $v = $l + $s * min($l, 1 - $l);
        $s = ($v == 0) ? 0 : 2 * (1 - $l / $v);

        return $this->colorFromNormalized([$h, $s, $v]);
    }
}
