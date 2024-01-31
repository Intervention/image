<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsl\Decoders;

use Intervention\Image\Colors\Hsl\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Decode hsl color strings
     *
     * @param mixed $input
     * @return ImageInterface|ColorInterface
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $pattern = '/^hsl\((?P<h>[0-9\.]+), ?(?P<s>[0-9\.]+%?), ?(?P<l>[0-9\.]+%?)?\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new DecoderException('Unable to decode input');
        }

        $values = array_map(function ($value) {
            return match (strpos($value, '%')) {
                false => intval(trim($value)),
                default => intval(trim(str_replace('%', '', $value))),
            };
        }, [$matches['h'], $matches['s'], $matches['l']]);

        return new Color(...$values);
    }
}
