<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class HexColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Decode hexadecimal rgb colors with and without transparency
     *
     * @param mixed $input
     * @return ImageInterface|ColorInterface
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $pattern = '/^#?(?P<hex>[a-f\d]{3}(?:[a-f\d]?|(?:[a-f\d]{3}(?:[a-f\d]{2})?)?)\b)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new DecoderException('Unable to decode input');
        }

        $values = match (strlen($matches['hex'])) {
            3, 4 => str_split($matches['hex']),
            6, 8 => str_split($matches['hex'], 2),
            default => throw new DecoderException('Unable to decode input'),
        };

        $values = array_map(function (string $value): float|int {
            return match (strlen($value)) {
                1 => hexdec($value . $value),
                2 => hexdec($value),
                default => throw new DecoderException('Unable to decode input'),
            };
        }, $values);

        return new Color(...$values);
    }
}
