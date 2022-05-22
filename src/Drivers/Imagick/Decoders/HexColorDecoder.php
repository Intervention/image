<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class HexColorDecoder extends ArrayColorDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $pattern = '/^#?([a-f0-9]{1,2})([a-f0-9]{1,2})([a-f0-9]{1,2})$/i';
        $result = preg_match($pattern, $input, $matches);

        if ($result !== 1) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode([
            strlen($matches[1]) == '1' ? hexdec($matches[1] . $matches[1]) : hexdec($matches[1]),
            strlen($matches[2]) == '1' ? hexdec($matches[2] . $matches[2]) : hexdec($matches[2]),
            strlen($matches[3]) == '1' ? hexdec($matches[3] . $matches[3]) : hexdec($matches[3]),
        ]);
    }
}
