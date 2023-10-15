<?php

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorInterface;

class Parser
{
    protected static $parsers = [
        Rgb\Parser::class,
        Rgba\Parser::class,
        Cmyk\Parser::class,
    ];

    public static function parse(mixed $value): ColorInterface
    {
        foreach (static::$parsers as $parser) {
            try {
                return forward_static_call([$parser, 'parse'], $value);
            } catch (ColorException $e) {
                # move on...
            }
        }

        throw new ColorException('Unable to parse color');
    }
}
