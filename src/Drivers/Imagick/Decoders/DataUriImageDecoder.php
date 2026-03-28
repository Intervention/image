<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\DataUri;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanDetectImageSources;

class DataUriImageDecoder extends BinaryImageDecoder
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
     * @throws ImageDecoderException
     * @throws DriverException
     * @throws StateException
     */
    public function decode(mixed $input): ImageInterface
    {
        if ($input instanceof DataUri) {
            try {
                return parent::decode($input->data());
            } catch (DecoderException) {
                throw new ImageDecoderException('Data Uri contains unsupported image type');
            }
        }

        if (!is_string($input)) {
            throw new InvalidArgumentException(
                'Image source must be data uri scheme of type string or ' . DataUri::class,
            );
        }

        try {
            return parent::decode(DataUri::parse($input)->data());
        } catch (DecoderException) {
            throw new ImageDecoderException('Data Uri contains unsupported image type');
        }
    }
}
