<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanDecodeDataUri;
use Intervention\MimeSniffer\MimeSniffer;

class DataUriImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    use CanDecodeDataUri;

    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            $this->fail();
        }

        $uri = $this->decodeDataUri($input);

        if (! $uri->isValid()) {
            $this->fail();
        }

        if ($uri->isBase64Encoded()) {
            return parent::decode(base64_decode($uri->data()));
        }

        return parent::decode(urldecode($uri->data()));
    }
}
