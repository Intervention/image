<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Format;
use Intervention\Image\Modifiers\AlignRotationModifier;
use Intervention\Image\Traits\CanDetectImageSources;
use Stringable;

class BinaryImageDecoder extends NativeObjectDecoder implements DecoderInterface
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
     * @throws NotSupportedException
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

        return $this->isGifFormat($input) ? $this->decodeGif($input) : $this->decodeBinary($input);
    }

    /**
     * Decode image from given binary data
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     * @throws StateException
     * @throws NotSupportedException
     */
    private function decodeBinary(string $input): ImageInterface
    {
        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            throw new ImageDecoderException('Failed to decode unsupported image format from binary data');
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
