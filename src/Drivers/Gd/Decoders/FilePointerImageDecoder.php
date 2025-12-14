<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use http\Exception\InvalidArgumentException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FilePointerImageDecoder extends BinaryImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_resource($input) || !in_array(get_resource_type($input), ['file', 'stream'])) {
            // NEWEX
            throw new InvalidArgumentException("Input must be a resource of type 'file' or 'stream'");
        }

        $contents = '';
        $result = rewind($input);

        if ($result === false) {
            // NEWEX
            throw new FilePointerException('Failed to rewind position of file pointer');
        }

        while (!feof($input)) {
            // NEWEX
            $contents .= fread($input, 1024) ?: throw new FilePointerException('Failed to read from file pointer');
        }

        return parent::decode($contents);
    }
}
