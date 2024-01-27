<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Decoders\Traits\CanDecodeGif;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Modifiers\AlignRotationModifier;

class BinaryImageDecoder extends AbstractDecoder implements DecoderInterface
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

        $image = match ($this->isGifFormat($input)) {
            true => $this->decodeGif($input),
            default => $this->decodeBinary($input),
        };

        return $image;
    }

    /**
     * Decode image from given binary data
     *
     * @param string $input
     * @return ImageInterface
     * @throws DecoderException
     */
    private function decodeBinary(string $input): ImageInterface
    {
        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            throw new DecoderException('Unable to decode input');
        }

        if (!imageistruecolor($gd)) {
            imagepalettetotruecolor($gd);
        }
        imagesavealpha($gd, true);

        // build image instance
        $image =  new Image(
            new Driver(),
            new Core([
                new Frame($gd)
            ]),
            $this->extractExifData($input)
        );

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
