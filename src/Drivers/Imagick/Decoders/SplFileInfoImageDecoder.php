<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use SplFileInfo;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class SplFileInfoImageDecoder extends FilePathImageDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! is_a($input, SplFileInfo::class)) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode($input->getRealPath());
    }
}
