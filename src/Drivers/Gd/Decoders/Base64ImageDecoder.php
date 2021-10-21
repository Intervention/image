<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanValidateBase64;

class Base64ImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    use CanValidateBase64;

    public function decode($input): ImageInterface|ColorInterface
    {
        if (! $this->isValidBase64($input)) {
            $this->fail();
        }

        return parent::decode(base64_decode($input));
    }
}
