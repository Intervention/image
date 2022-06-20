<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class BinaryImageDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!$this->inputType($input)->isBinary()) {
            throw new DecoderException('Unable to decode input');
        }

        $imagick = new Imagick();
        $imagick->readImageBlob($input);
        $imagick = $imagick->coalesceImages();

        $image = new Image($imagick);
        $image->setLoops($imagick->getImageIterations());

        return $image;
    }
}
