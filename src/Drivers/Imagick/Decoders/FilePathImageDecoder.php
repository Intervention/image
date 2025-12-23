<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Stringable;

class FilePathImageDecoder extends NativeObjectDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        if (!is_string($input) && !($input instanceof Stringable)) {
            return false;
        }

        $input = (string) $input;

        if (strlen($input) > PHP_MAXPATHLEN) {
            return false;
        }

        if (str_starts_with($input, DIRECTORY_SEPARATOR)) {
            return true;
        }

        if (preg_match('/[^ -~]/', $input) === 1) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface
    {
        // make sure path is valid
        $path = $this->parseFilePathOrFail($input);

        try {
            $imagick = new Imagick();
            $imagick->readImage($path);
        } catch (ImagickException) {
            throw new ImageDecoderException(
                'Failed to decode image data from file "' . $path . '"'
            );
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
