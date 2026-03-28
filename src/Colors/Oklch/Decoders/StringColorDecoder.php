<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklch\Decoders;

use Intervention\Image\Colors\Oklch\Channels\Chroma;
use Intervention\Image\Colors\Oklch\Channels\Hue;
use Intervention\Image\Colors\Oklch\Channels\Lightness;
use Intervention\Image\Colors\Oklch\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Regex pattern for oklch color syntax.
     */
    protected const string PATTERN =
        '/^oklch ?\( ?' .
        '(?P<l>(1|0|0?\.[0-9]+)|[0-9\.]+%)((, ?)|( ))' .
        '(?P<c>(-?0|-?0?\.[0-9\.]+)|(-?[0-9\.]+%))((, ?)|( ))' .
        '(?P<h>[0-9\.]+)' .
        '(?:(?:(?: ?\/ ?)|(?:[, ]) ?)' .
        '(?<a>(?:0\.[0-9]+)|1\.0|\.[0-9]+|[0-9]{1,3}%|1|0))?' .
        ' ?\)$/i';

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

        if (!str_starts_with(strtolower($input), 'oklch')) {
            return false;
        }

        return true;
    }

    /**
     * Decode hsl color string.
     *
     * @throws InvalidArgumentException
     */
    public function decode(mixed $input): ColorInterface
    {
        if (preg_match(self::PATTERN, $input, $matches) != 1) {
            throw new InvalidArgumentException('Invalid oklch() color syntax "' . $input . '"');
        }

        $values = [
            $this->decodeChannelValue($matches['l'], Lightness::class),
            $this->decodeChannelValue($matches['c'], Chroma::class),
            $this->decodeChannelValue($matches['h'], Hue::class),
        ];

        // alpha value
        if (array_key_exists('a', $matches)) {
            $values[] = $this->decodeAlphaChannelValue($matches['a']);
        }

        return new Color(...$values);
    }

    /**
     * Decode channel value.
     */
    private function decodeChannelValue(string $value, string $channel): float
    {
        if (strpos($value, '%')) {
            return floatval(trim(str_replace('%', '', $value))) * $channel::max() / 100;
        }

        return floatval(trim($value));
    }

    private function decodeAlphaChannelValue(string $value): float
    {
        if (strpos($value, '%')) {
            return floatval(trim(str_replace('%', '', $value))) / 100;
        }

        return floatval(trim($value));
    }
}
