<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\MediaType;
use Intervention\Image\Modifiers\AlignRotationModifier;
use Intervention\Image\Traits\CanDetectImageSources;
use Throwable;

class FilePathImageDecoder extends NativeObjectDecoder implements DecoderInterface
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
     * @throws ImageDecoderException
     * @throws DriverException
     * @throws StateException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws DirectoryNotFoundException
     */
    public function decode(mixed $input): ImageInterface
    {
        // make sure path is valid
        $path = $this->readableFilePathOrFail($input);

        try {
            // detect media (mime) type
            $mediaType = $this->getMediaTypeByFilePath($path);
        } catch (Throwable) {
            throw new ImageDecoderException('File contains unsupported image format');
        }

        $image = match ($mediaType->format()) {
            // gif files might be animated and therefore cannot
            // be handled by the standard GD decoder.
            Format::GIF => $this->decodeGif($path),
            default => $this->decodeDefault($path, $mediaType),
        };

        // set file path & mediaType on origin
        $image->origin()->setFilePath($path);
        $image->origin()->setMediaType($mediaType);

        // extract exif for the appropriate formats
        if ($mediaType->format() === Format::JPEG) {
            $image->setExif($this->extractExifData($path));
        }

        // adjust image orientation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        return $image;
    }

    /**
     * Try to decode data from file path as given image format
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws StateException
     * @throws DriverException
     */
    private function decodeDefault(string $path, MediaType $mediaType): ImageInterface
    {
        $gdImage = match ($mediaType->format()) {
            Format::JPEG => @imagecreatefromjpeg($path),
            Format::WEBP => @imagecreatefromwebp($path),
            Format::PNG => @imagecreatefrompng($path),
            Format::AVIF => @imagecreatefromavif($path),
            Format::BMP => @imagecreatefrombmp($path),
            default => throw new ImageDecoderException('File contains unsupported image format'),
        };

        if ($gdImage === false) {
            throw new ImageDecoderException(
                'Failed to decode data from file "' . $path . '" as image format "' . $mediaType->value . '"',
            );
        }

        try {
            return parent::decode($gdImage);
        } catch (DecoderException) {
            throw new ImageDecoderException(
                'Failed to decode data from file "' . $path . '" as image format "' . $mediaType->value . '"',
            );
        }
    }
}
