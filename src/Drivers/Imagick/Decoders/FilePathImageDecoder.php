<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FilePathImageDecoder extends NativeObjectDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        // make sure path is valid
        $path = $this->parseFilePath($input);

        try {
            $imagick = new Imagick();
            $imagick->readImage($path);
        } catch (ImagickException) {
            throw new DecoderException('File contains unsupported image format.');
        }

        // decode image
        $image = parent::decode($imagick);

        // set file path on origin
        $image->origin()->setFilePath($path);

        // extract exif data for the appropriate formats
        if (in_array($imagick->getImageFormat(), ['JPEG', 'TIFF', 'TIF'])) {
            $image->setExif($this->extractExifData($path));
        }

        return $image;
    }
}
