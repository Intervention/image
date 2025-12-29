<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsl\Decoders;

use Intervention\Image\Colors\Hsl\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    protected const string HSL_PATTERN =
        '/^hsl ?\(' .
        '(?P<h>[0-9\.]+)((, ?)| )' .
        '(?P<s>[0-9\.]+%?)((, ?)| )' .
        '(?P<l>[0-9\.]+%?)\)$/i';

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

        if (!str_starts_with(strtolower($input), 'hsl')) {
            return false;
        }

        return true;
    }

    /**
     * Decode hsl color strings
     */
    public function decode(mixed $input): ColorInterface
    {
        if (preg_match(self::HSL_PATTERN, $input, $matches) != 1) {
            throw new InvalidArgumentException('Invalid hsl() color syntax');
        }

        $values = array_map(function (string $value): int {
            return match (strpos($value, '%')) {
                false => intval(trim($value)),
                default => intval(trim(str_replace('%', '', $value))),
            };
        }, [$matches['h'], $matches['s'], $matches['l']]);

        return new Color(...$values);
    }
}
