<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\DataUri;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanDetectImageSources;

class DataUriImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    use CanDetectImageSources;

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $this->couldBeDataUrl($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     * @throws ImageDecoderException
     * @throws StateException
     * @throws NotSupportedException
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
            throw new ImageDecoderException('Data Uri contains unsupported image type');
        }
    }
}
