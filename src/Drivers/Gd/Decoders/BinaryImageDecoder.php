<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Format;
use Intervention\Image\Modifiers\AlignRotationModifier;
use Stringable;

class BinaryImageDecoder extends NativeObjectDecoder implements DecoderInterface
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
            throw new InvalidArgumentException('Input does not contain binary image data');
        }

        return match ($this->isGifFormat($input)) {
            true => $this->decodeGif($input),
            default => $this->decodeBinary($input),
        };
    }

    /**
     * Decode image from given binary data
     */
    private function decodeBinary(string $input): ImageInterface
    {
        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            throw new DecoderException('Binary data contains unsupported image type');
        }

        // create image instance
        $image = parent::decode($gd);

        // get media type
        $mediaType = $this->getMediaTypeByBinary($input);

        // extract & set exif data for appropriate formats
        if (in_array($mediaType->format(), [Format::JPEG, Format::TIFF])) {
            $image->setExif($this->extractExifData($input));
        }

        // set mediaType on origin
        $image->origin()->setMediaType($mediaType);

        // adjust image orientation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        return $image;
    }
}
