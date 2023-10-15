<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Colors\Rgb\Parser;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class HtmlColorNameDecoder extends HexColorDecoder
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            return Parser::fromName($input);
        } catch (ColorException $e) {
            # code ...
        }

        throw new DecoderException('Unable to decode input');
    }
}
