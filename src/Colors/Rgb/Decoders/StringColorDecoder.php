<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    private const string PATTERN =
        '/^s?rgba? ?\(' .
        '(?P<r>[0-9\.]+%?)((, ?)| )' .
        '(?P<g>[0-9\.]+%?)((, ?)| )' .
        '(?P<b>[0-9\.]+%?)(?:((, ?)| )' .
        '(?P<a>(?:1)|(?:1\.0*)|(?:0)|(?:0?\.\d+%?)|(?:\d{1,3}%)))?\)$/i';

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

        if (preg_match('/^s?rgb/i', $input) != 1) {
            return false;
        }

        return true;
    }

    /**
     * Decode rgb color strings
     */
    public function decode(mixed $input): ColorInterface
    {
        if (preg_match(self::PATTERN, $input, $matches) != 1) {
            throw new InvalidArgumentException('Invalid rgb() color syntax "' . $input . '"');
        }

        // rgb values
        $values = array_map(function (string $value): int {
            return match (strpos($value, '%')) {
                false => intval(trim($value)),
                default => intval(round(floatval(trim(str_replace('%', '', $value))) / 100 * 255)),
            };
        }, [$matches['r'], $matches['g'], $matches['b']]);

        // alpha value
        if (array_key_exists('a', $matches)) {
            $values[] = match (strpos($matches['a'], '%')) {
                false => floatval(trim($matches['a'])),
                default => floatval(trim(str_replace('%', '', $matches['a']))) / 100,
            };
        }

        return new Color(...$values);
    }
}
