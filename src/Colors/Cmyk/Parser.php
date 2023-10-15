<?php

namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Exceptions\ColorException;

class Parser
{
    public static function parse(mixed $value): Color
    {
        if (!is_string($value)) {
            throw new ColorException('Unable to parse color');
        }

        return static::fromString($value);
    }

    public static function fromString(string $input): Color
    {
        // cmyk(100%, 100%, 100%, 100%)
        $pattern = '/^cmyk\((?P<c>[0-9\.]+)%?, ?(?P<m>[0-9\.]+)%?, ?(?P<y>[0-9\.]+)%?, ?(?P<k>[0-9\.]+)%?\)$/';
        $result = preg_match($pattern, $input, $matches);
        if ($result === 1) {
            return new Color(
                intval(round(floatval($matches['c']))),
                intval(round(floatval($matches['m']))),
                intval(round(floatval($matches['y']))),
                intval(round(floatval($matches['k'])))
            );
        }

        throw new ColorException('Unable to parse color');
    }
}
