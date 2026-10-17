<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;

class Psr7StreamImageDecoder extends BinaryImageDecoder
{
    private const string DECODABLE_CLASSNAME = 'Psr\Http\Message\StreamInterface';

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $this->isPsr7Stream($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     *
     * @throws InvalidArgumentException
     * @throws StreamException
     * @throws DriverException
     * @throws StateException
     * @throws ImageDecoderException
     * @throws NotSupportedException
     */
    public function decode(mixed $input): ImageInterface
    {
        if (!$this->isPsr7Stream($input)) {
            throw new InvalidArgumentException(
                "Image source must be of type '" . self::DECODABLE_CLASSNAME . "'",
            );
        }

        try {
            return parent::decode((string) $input);
        } catch (DecoderException) {
            throw new ImageDecoderException(
                "Failed to decode image from 'GuzzleHttp\Psr7\Response', could be unsupported image format",
            );
        }
    }

    private function isPsr7Stream(mixed $input): bool
    {
        return is_object($input) && is_a($input, self::DECODABLE_CLASSNAME);
    }
}
