<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class TransparentColorDecoder extends ArrayColorDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! is_string($input) || strtolower($input) !== 'transparent') {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode([0, 0, 0, 0]);
    }
}
