<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsv;

use Intervention\Image\Colors\AbstractColorspace;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Colors\Rgb\NamedColor;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use TypeError;

class Colorspace extends AbstractColorspace
{
    /**
     * Channel class names of colorspace.
     *
     * @var array<string>
     */
    public static array $channels = [
        Channels\Hue::class,
        Channels\Saturation::class,
        Channels\Value::class,
        Channels\Alpha::class,
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     *
     * @throws InvalidArgumentException
     */
    public static function colorFromNormalized(array $normalized): HsvColor
    {
        if (!in_array(count($normalized), [3, 4])) {
            throw new InvalidArgumentException('Number of color channels must be 3 or 4 for ' . static::class);
        }

        // add alpha value if missing
        $normalized = count($normalized) === 3 ? array_pad($normalized, 4, 1) : $normalized;

        return new Color(...array_map(
            function (string $channel, null|float $normalized) {
                try {
                    return $channel::fromNormalized($normalized);
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
     * @throws InvalidArgumentException
     * @throws NotSupportedException
     * @throws ColorDecoderException
     */
    public function importColor(ColorInterface $color): HsvColor
    {
        return match ($color::class) {
            CmykColor::class,
            OklchColor::class,
            NamedColor::class,
            OklabColor::class => $this->importViaRgbColor($color),
            RgbColor::class => $this->importRgbColor($color),
            HslColor::class => $this->importHslColor($color),
            HsvColor::class => $color,
            default => throw new NotSupportedException(
                'Unable to import color ' . $color::class . ' to ' . $this::class,
            ),
        };
    }

    /**
     * Import given RGB color HSV colorspace.
     */
    private function importRgbColor(RgbColor $color): HsvColor
    {
        // normalized values of rgb channels
        $values = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
            $color->channels(),
        );

        // take only RGB
        $values = array_slice($values, 0, 3);

        // calculate chroma
        $min = min(...$values);
        $max = max(...$values);
        $chroma = $max - $min;

        // calculate value
        $v = 100 * $max;

        if ($chroma == 0) {
            // grayscale color
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
            intval(round($v)),
            $color->alpha()->normalizedValue(),
        );
    }

    /**
     * Import given HSL color HSV colorspace.
     *
     * @throws InvalidArgumentException
     */
    protected function importHslColor(ColorInterface $color): HsvColor
    {
        if (!$color instanceof HslColor) {
            throw new InvalidArgumentException('Color must be of type ' . HslColor::class);
        }

        // normalized values of hsl channels
        [$h, $s, $l] = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
            $color->channels()
        );

        $v = $l + $s * min($l, 1 - $l);
        $s = ($v == 0) ? 0 : 2 * (1 - $l / $v);

        return $this->colorFromNormalized([$h, $s, $v, $color->alpha()->normalizedValue()]);
    }

    /**
     * Import given color to HSV color space by converting it to RGB first.
     *
     * @throws ColorDecoderException
     */
    private function importViaRgbColor(NamedColor|CmykColor|OklchColor|OklabColor $color): HsvColor
    {
        try {
            $color = $color->toColorspace(RgbColorspace::class);
        } catch (InvalidArgumentException | NotSupportedException $e) {
            throw new ColorDecoderException(
                'Failed to transform color to HSV color space',
                previous: $e
            );
        }

        if (!$color instanceof RgbColor) {
            throw new ColorDecoderException('Failed to transform color to HSV color space');
        }

        return $this->importRgbColor($color);
    }
}
