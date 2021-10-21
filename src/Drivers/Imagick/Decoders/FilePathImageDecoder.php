<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Exception;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FilePathImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! is_string($input)) {
            $this->fail();
        }

        try {
            if (! @is_file($input)) {
                $this->fail();
            }
        } catch (Exception $e) {
            $this->fail();
        }

        return parent::decode(file_get_contents($input));
    }
}
