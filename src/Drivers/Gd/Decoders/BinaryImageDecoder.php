<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Decoders\Traits\CanDecodeGif;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Modifiers\AlignRotationModifier;

class BinaryImageDecoder extends GdImageDecoder implements DecoderInterface
{
    use CanDecodeGif;

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        return match ($this->isGifFormat($input)) {
            true => $this->decodeGif($input),
            default => $this->decodeBinary($input),
        };
    }

    /**
     * Decode image from given binary data
     *
     * @param string $input
     * @throws RuntimeException
     * @return ImageInterface
     */
    private function decodeBinary(string $input): ImageInterface
    {
        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            throw new DecoderException('Unable to decode input');
        }

        // create image instance
        $image = parent::decode($gd);

        // extract & set exif data
        $image->setExif($this->extractExifData($input));

        try {
            // set mediaType on origin
            $image->origin()->setMediaType(
                $this->getMediaTypeByBinary($input)
            );
        } catch (DecoderException) {
        }

        // adjust image orientation
        $image->modify(new AlignRotationModifier());

        return $image;
    }
}
