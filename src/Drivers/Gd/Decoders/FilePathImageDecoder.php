<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use Exception;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FilePathImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            if (! @is_file($input)) {
                throw new DecoderException('Unable to decode input');
            }
        } catch (Exception $e) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode(file_get_contents($input));
    }
}
