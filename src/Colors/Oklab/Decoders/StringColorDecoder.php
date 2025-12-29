<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklab\Decoders;

use Intervention\Image\Colors\Oklab\Color;
use Intervention\Image\Colors\Oklab\Channels\Lightness;
use Intervention\Image\Colors\Oklab\Channels\A;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    private const string PATTERN =
        '/^oklab ?\(' .
        '(?P<l>(1|0|0?\.[0-9]+)|[0-9\.]+%)((, ?)|( ))' .
        '(?P<a>(-?0|-?0?\.[0-9\.]+)|(-?[0-9\.]+%))((, ?)|( ))' .
        '(?P<b>(-?0|-?0?\.[0-9\.]+)|(-?[0-9\.]+%))' .
        '\)$/i';

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

        if (!str_starts_with(strtolower($input), 'oklab')) {
            return false;
        }

        return true;
    }

    /**
     * Decode hsl color strings
     */
    public function decode(mixed $input): ColorInterface
    {
        if (preg_match(self::PATTERN, $input, $matches) != 1) {
            throw new InvalidArgumentException('Invalid oklab() color syntax "' . $input . '"');
        }

        return new Color(...[
            $this->decodeLightness($matches['l']),
            $this->decodeAxis($matches['a']),
            $this->decodeAxis($matches['b']),
        ]);
    }

    /**
     * Decode lightness value
     */
    private function decodeLightness(string $value): float
    {
        if (strpos($value, '%')) {
            return floatval(trim(str_replace('%', '', $value))) * Lightness::max() / 100;
        }

        return floatval(trim($value));
    }

    /**
     * Decode axis (a, b) values
     */
    private function decodeAxis(string $value): float
    {
        if (strpos($value, '%')) {
            return floatval(trim(str_replace('%', '', $value))) * A::max() / 100;
        }

        return floatval(trim($value));
    }
}
