<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Exception;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FilePathImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            if (!@is_file($input)) {
                throw new DecoderException('Unable to decode input');
            }
        } catch (Exception) {
            throw new DecoderException('Unable to decode input');
        }

        // decode image
        $image =  parent::decode(file_get_contents($input));

        // set file path on origin
        $image->origin()->setFilePath($input);

        return $image;
    }
}
