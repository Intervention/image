<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class HexColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Decode hexadecimal rgb colors with and without transparency
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new InvalidArgumentException('Input must be of type string');
        }

        $pattern = '/^#?(?P<hex>[a-f\d]{3}(?:[a-f\d]?|(?:[a-f\d]{3}(?:[a-f\d]{2})?)?)\b)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new InvalidArgumentException('Input must be valid hex color');
        }

        $values = match (strlen($matches['hex'])) {
            3, 4 => str_split($matches['hex']),
            6, 8 => str_split($matches['hex'], 2),
            default => throw new InvalidArgumentException('Hex color has an incorrect length'),
        };

        $values = array_map(function (string $value): int {
            return match (strlen($value)) {
                1 => (int) hexdec($value . $value),
                2 => (int) hexdec($value),
                default => throw new InvalidArgumentException('Input must be valid hex color'),
            };
        }, $values);

        return new Color(...$values);
    }
}
