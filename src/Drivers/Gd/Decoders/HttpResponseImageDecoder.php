<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;

class HttpResponseImageDecoder extends BinaryImageDecoder
{
    private const string DECODABLE_CLASSNAME = 'Psr\Http\Message\ResponseInterface';

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $this->isPsr7Response($input);
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
        if (!$this->isPsr7Response($input)) {
            throw new InvalidArgumentException(
                "Image source must be of type '" . self::DECODABLE_CLASSNAME . "'",
            );
        }

        try {
            return parent::decode($input->getBody());
        } catch (DecoderException) {
            throw new ImageDecoderException(
                "Failed to decode image from " . self::DECODABLE_CLASSNAME . ", could be unsupported image format",
            );
        }
    }

    /**
     * Determine if input can be handled as psr7 response.
     */
    private function isPsr7Response(mixed $input): bool
    {
        return is_object($input) && is_a($input, self::DECODABLE_CLASSNAME);
    }
}
