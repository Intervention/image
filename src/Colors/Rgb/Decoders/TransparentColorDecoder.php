<?php

namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;

class TransparentColorDecoder extends HexColorDecoder
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (strtolower($input) != 'transparent') {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode('#ff00ff00');
    }
}
