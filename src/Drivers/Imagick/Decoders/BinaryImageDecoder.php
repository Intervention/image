<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\ImageInterface;
use Stringable;

class BinaryImageDecoder extends NativeObjectDecoder
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

        // contains non printable ascii
        if (preg_match('/[^ -~]/', $input) === 1) {
            return true;
        }

        // contains only printable ascii
        if (preg_match('/^[ -~]+$/', $input) === 1) {
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
        if (!is_string($input) && !($input instanceof Stringable)) {
            throw new InvalidArgumentException('Binary data must be either of type string or instance of Stringable');
        }

        $input = (string) $input;

        if (empty($input)) {
            throw new InvalidArgumentException('Unable to decode binary data from empty string');
        }

        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($input);
        } catch (ImagickException) {
            throw new DecoderException('Failed to decode binary data, could be unsupported image type');
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
