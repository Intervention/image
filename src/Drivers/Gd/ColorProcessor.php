<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Traits\CanConvertRange;

class ColorProcessor implements ColorProcessorInterface
{
    use CanConvertRange;

    /**
     * {@inheritdoc}
     *
     * @see ColorProcessorInterface::colorspace()
     */
    public function colorspace(): ColorspaceInterface
    {
        return new Rgb();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorProcessorInterface::export()
     */
    public function export(ColorInterface $color): int
    {
        // convert color to colorspace
        $color = $color->toColorspace($this->colorspace());

        // gd only supports rgb so the channels can be accessed directly
        $r = $color->channel(Red::class)->value();
        $g = $color->channel(Green::class)->value();
        $b = $color->channel(Blue::class)->value();
        $a = $color->channel(Alpha::class)->value();

        // convert alpha value to gd alpha
        // ([opaque]1-0[transparent]) to ([opaque]0-127[transparent])
        $a = (int) round(self::convertRange($a, Alpha::min(), Alpha::max(), 127, 0));

        return ($a << 24) + ($r << 16) + ($g << 8) + $b;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorProcessorInterface::import()
     *
     * @throws ColorDecoderException
     */
    public function import(mixed $color): ColorInterface
    {
        if (!is_int($color) && !is_array($color)) {
            throw new ColorDecoderException('GD driver can only decode colors in integer or array format');
        }

        if (is_array($color)) {
            // array conversion
            if (!$this->isValidArrayColor($color)) {
                throw new ColorDecoderException(
                    'GD driver can only decode array color format array{red: int, green: int, blue: int, alpha: int}',
                );
            }

            $r = $color['red'];
            $g = $color['green'];
            $b = $color['blue'];
            $a = $color['alpha'];
        } else {
            // integer conversion
            $a = ($color >> 24) & 0xFF;
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = $color & 0xFF;
        }

        // convert gd apha integer to intervention alpha integer
        // ([opaque]0-127[transparent]) to ([opaque]1-0[transparent])
        $a = self::convertRange($a, 127, 0, 0, 1);

        return new Color($r, $g, $b, $a);
    }

    /**
     * Check if given array is valid color format
     * array{red: int, green: int, blue: int, alpha: int}
     * i.e. result of imagecolorsforindex()
     *
     * @param array<mixed> $color
     */
    private function isValidArrayColor(array $color): bool
    {
        if (!array_key_exists('red', $color)) {
            return false;
        }

        if (!array_key_exists('green', $color)) {
            return false;
        }

        if (!array_key_exists('blue', $color)) {
            return false;
        }

        if (!array_key_exists('alpha', $color)) {
            return false;
        }

        if (!is_int($color['red'])) {
            return false;
        }

        if (!is_int($color['green'])) {
            return false;
        }

        if (!is_int($color['blue'])) {
            return false;
        }

        if (!is_int($color['alpha'])) {
            return false;
        }

        return true;
    }
}
