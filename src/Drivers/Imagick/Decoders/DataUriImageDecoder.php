<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\DataUri;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;

class DataUriImageDecoder extends BinaryImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return is_string($input) && str_starts_with($input, 'data:');
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface
    {
        $input = ($input instanceof DataUri) ? (string) $input : $input;

        if (!is_string($input)) {
            throw new InvalidArgumentException('Data Uri must be of type string or ' . DataUri::class);
        }

        $data = DataUri::decode($input)->data();

        try {
            return parent::decode($data);
        } catch (DecoderException) {
            throw new DecoderException('Data Uri contains unsupported image type');
        }
    }
}
