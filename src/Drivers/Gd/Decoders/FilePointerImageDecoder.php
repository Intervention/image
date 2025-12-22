<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
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
     */
    public function decode(mixed $input): ImageInterface
    {
        if (!is_resource($input) || !in_array(get_resource_type($input), ['file', 'stream'])) {
            throw new InvalidArgumentException("Input must be a resource of type 'file' or 'stream'");
        }

        $contents = '';
        $result = rewind($input);

        if ($result === false) {
            throw new FilePointerException('Failed to rewind position of file pointer');
        }

        while (!feof($input)) {
            $contents .= fread($input, 1024) ?: throw new FilePointerException('Failed to read from file pointer');
        }

        return parent::decode($contents);
    }
}
