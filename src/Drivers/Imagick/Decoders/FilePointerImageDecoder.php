<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;

class FilePointerImageDecoder extends BinaryImageDecoder
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
     * @throws FilePointerException
     * @throws DriverException
     * @throws StateException
     */
    public function decode(mixed $input): ImageInterface
    {
        if (!is_resource($input) || !in_array(get_resource_type($input), ['file', 'stream'])) {
            throw new InvalidArgumentException('Input must be a resource of type "file" or "stream"');
        }

        $contents = '';
        $rewind = rewind($input);
        if ($rewind === false) {
            throw new FilePointerException('Failed to rewind position of file pointer');
        }

        while (!feof($input)) {
            $chunk = fread($input, 1024);
            if ($chunk === false) {
                throw new FilePointerException('Failed to read from file pointer');
            }

            $contents .= $chunk;
        }

        try {
            return parent::decode($contents);
        } catch (DecoderException) {
            throw new ImageDecoderException(
                'Failed to decode image from file pointer, could be unsupported image format',
            );
        }
    }
}
