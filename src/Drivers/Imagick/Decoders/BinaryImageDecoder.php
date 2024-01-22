<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class BinaryImageDecoder extends ImagickImageDecoder implements DecoderInterface
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($input);
        } catch (ImagickException) {
            throw new DecoderException('Unable to decode input');
        }

        // decode image
        $image = parent::decode($imagick);

        // extract exif data
        $image->setExif($this->extractExifData($input));

        return $image;
    }
}
