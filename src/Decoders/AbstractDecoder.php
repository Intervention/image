<?php

namespace Intervention\Image\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class AbstractDecoder implements DecoderInterface
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        throw new DecoderException('Object must be specialized by the driver first.');
    }
}
