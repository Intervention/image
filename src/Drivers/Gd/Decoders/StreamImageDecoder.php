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

class StreamImageDecoder extends BinaryImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return is_resource($input);
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
        if (!is_resource($input) || !in_array(get_resource_type($input), ['file', 'stream'])) {
            throw new InvalidArgumentException("Image source must be a resource of type 'file' or 'stream'");
        }

        $contents = '';
        $result = rewind($input);

        if ($result === false) {
            throw new StreamException('Failed to rewind position of stream');
        }

        while (!feof($input)) {
            $chunk = fread($input, 1024);
            if ($chunk === false) {
                throw new StreamException('Failed to read image from stream');
            }

            $contents .= $chunk;
        }

        try {
            return parent::decode($contents);
        } catch (DecoderException) {
            throw new ImageDecoderException(
                'Failed to decode image from stream, could be unsupported image format',
            );
        }
    }
}
