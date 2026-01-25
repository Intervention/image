<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanDetectImageSources;

class Base64ImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    use CanDetectImageSources;

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $this->couldBeBase64Data($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface
    {
        try {
            $data = $this->decodeBase64Data($input);
        } catch (DecoderException) {
            throw new ImageDecoderException('Unable to decode Base64-encoded string');
        }

        try {
            return parent::decode($data);
        } catch (DecoderException) {
            throw new ImageDecoderException('Base64-encoded data contains unsupported image type');
        }
    }
}
