<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;

class HexColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Pattern of hexadecimal colors
     */
    private const string PATTERN = '/^#?(?P<hex>[a-f\d]{3}(?:[a-f\d]?|(?:[a-f\d]{3}(?:[a-f\d]{2})?)?)\b)$/i';

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        if (!is_string($input)) {
            return false;
        }

        if (str_starts_with($input, '#')) {
            return true;
        }

        // matching max. length & only hexadecimal
        if (strlen($input) <= 8 && preg_match('/^[a-f\d]+$/i', $input) === 1) {
            return true;
        }

        return preg_match(self::PATTERN, $input) === 1;
    }

    /**
     * Decode hexadecimal rgb colors with and without transparency
     */
    public function decode(mixed $input): ColorInterface
    {
        if (preg_match(self::PATTERN, $input, $matches) != 1) {
            // TODO: decide exception type
            throw new InvalidArgumentException('Hex color has an invalid format');
        }

        $values = match (strlen($matches['hex'])) {
            3, 4 => str_split($matches['hex']),
            6, 8 => str_split($matches['hex'], 2),
            // TODO: decide exception type
            default => throw new InvalidArgumentException('Hex color has an incorrect length'),
        };

        $values = array_map(function (string $value): int {
            return match (strlen($value)) {
                1 => (int) hexdec($value . $value),
                2 => (int) hexdec($value),
                // TODO: decide exception type & message
                default => throw new InvalidArgumentException('Input must be valid hex color'),
            };
        }, $values);

        return new Color(...$values);
    }
}
