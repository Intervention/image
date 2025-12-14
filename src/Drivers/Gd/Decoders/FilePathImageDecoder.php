<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Modifiers\AlignRotationModifier;
use Throwable;

class FilePathImageDecoder extends NativeObjectDecoder implements DecoderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        // make sure path is valid
        $path = $this->parseFilePathOrFail($input);

        try {
            // detect media (mime) type
            $mediaType = $this->getMediaTypeByFilePath($path);
        } catch (Throwable) {
            // NEWEX
            throw new DecoderException('File contains unsupported image format');
        }

        $image = match ($mediaType->format()) {
            // gif files might be animated and therefore cannot
            // be handled by the standard GD decoder.
            Format::GIF => $this->decodeGif($path),
            default => parent::decode(match ($mediaType->format()) {
                Format::JPEG => @imagecreatefromjpeg($path),
                Format::WEBP => @imagecreatefromwebp($path),
                Format::PNG => @imagecreatefrompng($path),
                Format::AVIF => @imagecreatefromavif($path),
                Format::BMP => @imagecreatefrombmp($path),
                // NEWEX
                default => throw new DecoderException('File contains unsupported image format'),
            }),
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
}
