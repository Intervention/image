<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Cmyk;

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
        Channels\Cyan::class,
        Channels\Magenta::class,
        Channels\Yellow::class,
        Channels\Key::class,
        Channels\Alpha::class,
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     */
    public static function colorFromNormalized(array $normalized): CmykColor
    {
        if (!in_array(count($normalized), [4, 5])) {
            throw new InvalidArgumentException('Number of color channels must be 4 or 5 for ' . static::class);
        }

        // add alpha value if missing
        $normalized = count($normalized) === 4 ? array_pad($normalized, 5, 1) : $normalized;

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
    public function importColor(ColorInterface $color): CmykColor
    {
        return match ($color::class) {
            OklchColor::class,
            OklabColor::class,
            HsvColor::class,
            NamedColor::class,
            HslColor::class => $this->importViaRgbColor($color),
            RgbColor::class => $this->importRgbColor($color),
            CmykColor::class => $color,
            default => throw new NotSupportedException(
                'Unable to import color ' . $color::class . ' to ' . $this::class,
            ),
        };
    }

    /**
     * Import given RGB color to CMYK colorspace
     */
    private function importRgbColor(RgbColor $color): CmykColor
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

    /**
     * Import given color to CMYK colorspace by converting it to RGB first
     *
     * @throws ColorDecoderException
     */
    private function importViaRgbColor(NamedColor|OklabColor|OklchColor|HslColor|HsvColor $color): CmykColor
    {
        try {
            $color = $color->toColorspace(RgbColorspace::class);
        } catch (InvalidArgumentException | NotSupportedException $e) {
            throw new ColorDecoderException(
                'Failed to transform color to CMYK color space',
                previous: $e
            );
        }

        if (!$color instanceof RgbColor) {
            throw new ColorDecoderException(
                'Failed to transform color to CMYK color space',
            );
        }

        return $this->importRgbColor($color);
    }
}
