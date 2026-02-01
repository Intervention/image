<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanDetectImageSources;
use Stringable;

class BinaryImageDecoder extends NativeObjectDecoder
{
    use CanDetectImageSources;

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $this->couldBeBinaryData($input);
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
     */
    public function decode(mixed $input): ImageInterface
    {
        if (!is_string($input) && !$input instanceof Stringable) {
            throw new InvalidArgumentException(
                'Image source must be binary data of type string or instance of ' . Stringable::class,
            );
        }

        $input = (string) $input;

        if (empty($input)) {
            throw new InvalidArgumentException('Unable to decode binary data from empty string');
        }

        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($input);
        } catch (ImagickException) {
            throw new ImageDecoderException('Failed to decode unsupported image format from binary data');
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
