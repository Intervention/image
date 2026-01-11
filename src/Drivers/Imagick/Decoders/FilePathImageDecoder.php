<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanDetectImageSources;

class FilePathImageDecoder extends NativeObjectDecoder
{
    use CanDetectImageSources;

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $this->couldBeFilePath($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     *
     * @throws InvalidArgumentException
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws DriverException
     * @throws StateException
     * @throws ImageDecoderException
     */
    public function decode(mixed $input): ImageInterface
    {
        // make sure path is valid
        $path = $this->readableFilePathOrFail($input);

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
