<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Colors\Rgb\Parser as RgbColorParser;
use Intervention\Image\Colors\Rgba\Parser as RgbaColorParser;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class RgbStringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            return RgbColorParser::fromString($input);
        } catch (ColorException $e) {
            # code ...
        }

        try {
            return RgbaColorParser::fromString($input);
        } catch (ColorException $e) {
            # code ...
        }

        throw new DecoderException('Unable to decode input');
    }
}
