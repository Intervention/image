<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class BinaryImageDecoder extends NativeObjectDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input) || empty($input)) {
            throw new DecoderException('Input does not contain binary image data.');
        }

        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($input);
        } catch (ImagickException) {
            throw new DecoderException('Unsupported image format.');
        }

        // decode image
        $image = parent::decode($imagick);

        // get media type enum from string media type
        $format = Format::tryCreate($image->origin()->mediaType());

        // extract exif data for appropriate formats
        if (in_array($format, [Format::JPEG, Format::TIFF])) {
            $image->setExif($this->extractExifData($input));
        }

        return $image;
    }
}
