<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklch;

use Intervention\Image\Colors\AbstractColorspace;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklch\Channels\Alpha;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Colors\Oklch\Channels\Chroma;
use Intervention\Image\Colors\Oklch\Channels\Hue;
use Intervention\Image\Colors\Oklch\Channels\Lightness;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
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
        Lightness::class,
        Chroma::class,
        Hue::class,
        Alpha::class,
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     */
    public static function colorFromNormalized(array $normalized): OklchColor
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
     * @throws NotSupportedException
     * @throws ColorDecoderException
     */
    public function importColor(ColorInterface $color): OklchColor
    {
        return match ($color::class) {
            CmykColor::class,
            HsvColor::class,
            HslColor::class => $this->importViaRgbColor($color),
            OklabColor::class => $this->importOklabColor($color),
            RgbColor::class => $this->importRgbColor($color),
            OklchColor::class => $color,
            default => throw new NotSupportedException(
                'Unable to import color ' . $color::class . ' to ' . $this::class,
            ),
        };
    }

    /**
     * Import given OKLAB color OKLCH colorspace.
     */
    private function importOklabColor(OklabColor $color): OklchColor
    {
        $a = $color->a()->value();
        $b = $color->b()->value();

        $c = sqrt($a * $a + $b * $b);
        $h = rad2deg(atan2($b, $a));
        $h = $h < 0 ? $h + 360 : $h;

        return new Color($color->lightness()->value(), $c, $h, $color->alpha()->normalizedValue());
    }

    /**
     * Import given RGB color to OKLCH color space.
     *
     * @throws ColorDecoderException
     */
    private function importRgbColor(RgbColor $color): OklchColor
    {
        try {
            $color = $color->toColorspace(Oklab::class);
        } catch (InvalidArgumentException | NotSupportedException $e) {
            throw new ColorDecoderException(
                'Failed to transform RGB color to OKLCH color space',
                previous: $e
            );
        }

        if (!$color instanceof OklabColor) {
            throw new ColorDecoderException('Failed to transform RGB color to OKLCH color space');
        }

        return $this->importOklabColor($color);
    }

    /**
     * Import given color to OKLCH color space by converting it to RGB first.
     *
     * @throws ColorDecoderException
     */
    private function importViaRgbColor(HslColor|HsvColor|CmykColor $color): OklchColor
    {
        try {
            $color = $color->toColorspace(Rgb::class)->toColorspace($this::class);
        } catch (InvalidArgumentException | NotSupportedException $e) {
            throw new ColorDecoderException(
                'Failed to transform color to OKLCH color space',
                previous: $e
            );
        }

        if (!$color instanceof OklchColor) {
            throw new ColorDecoderException(
                'Failed to transform color to OKLCH color space',
            );
        }

        return $color;
    }
}
