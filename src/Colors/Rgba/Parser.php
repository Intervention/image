<?php

namespace Intervention\Image\Colors\Rgba;

use Intervention\Image\Colors\Rgb\Parser as RgbParser;
use Intervention\Image\Exceptions\ColorException;

class Parser extends RgbParser
{
    public static function fromHex(string $input): Color
    {
        try {
            return parent::fromHex($input)->toRgba();
        } catch (ColorException $e) {
            // move on
        }

        $pattern = '/^#?(?P<hex>[0-9a-f]{4}|[0-9a-f]{8})$/i';
        $result = preg_match($pattern, $input, $matches);

        if ($result !== 1) {
            throw new ColorException('Unable to parse color');
        }

        $matches = match (strlen($matches['hex'])) {
            4 => str_split($matches['hex']),
            8 => str_split($matches['hex'], 2),
            default => throw new ColorException('Unable to parse color'),
        };

        return new Color(
            strlen($matches[0]) == '1' ? hexdec($matches[0] . $matches[0]) : hexdec($matches[0]),
            strlen($matches[1]) == '1' ? hexdec($matches[1] . $matches[1]) : hexdec($matches[1]),
            strlen($matches[2]) == '1' ? hexdec($matches[2] . $matches[2]) : hexdec($matches[2]),
            strlen($matches[3]) == '1' ? hexdec($matches[3] . $matches[3]) : hexdec($matches[3]),
        );
    }

    public static function fromString(string $input): Color
    {
        $pattern = '/^rgba\((?P<r>[0-9]{1,3}), *(?P<g>[0-9]{1,3}), *(?P<b>[0-9]{1,3}), *(?P<a>((1|0))?(\.[0-9]+)?)\)$/';
        $result = preg_match($pattern, $input, $matches);

        if ($result !== 1) {
            throw new ColorException('Unable to parse color');
        }

        return new Color(
            $matches['r'],
            $matches['g'],
            $matches['b'],
            intval(round(floatval($matches['a']) * 255))
        );
    }
}
