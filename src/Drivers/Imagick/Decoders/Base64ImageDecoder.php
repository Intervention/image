<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class Base64ImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! $this->isValidBase64($input)) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode(base64_decode($input));
    }
}
