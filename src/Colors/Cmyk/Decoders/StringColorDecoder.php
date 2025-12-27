<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Cmyk\Decoders;

use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    protected const string CMYK_PATTERN =
        '/^cmyk ?\(' .
        '(?P<c>[0-9\.]+%?)((, ?)| )' .
        '(?P<m>[0-9\.]+%?)((, ?)| )' .
        '(?P<y>[0-9\.]+%?)((, ?)| )' .
        '(?P<k>[0-9\.]+%?)\)$/i';

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

        if (!str_starts_with(strtolower($input), 'cmyk')) {
            return false;
        }

        return true;
    }

    /**
     * Decode CMYK color strings
     */
    public function decode(mixed $input): ColorInterface
    {
        if (preg_match(self::CMYK_PATTERN, (string) $input, $matches) != 1) {
            throw new InvalidArgumentException('Invalid cmyk() color notation');
        }

        $values = array_map(function (string $value): int {
            return intval(round(floatval(trim(str_replace('%', '', $value)))));
        }, [$matches['c'], $matches['m'], $matches['y'], $matches['k']]);

        return new Color(...$values);
    }
}
