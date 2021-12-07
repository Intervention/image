<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ImageObjectDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! is_a($input, ImageInterface::class)) {
            $this->fail();
        }

        return $input;
    }
}
