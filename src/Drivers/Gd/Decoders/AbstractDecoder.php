<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\MediaType;
use Intervention\Image\Traits\CanParseFilePath;
use ValueError;

abstract class AbstractDecoder extends SpecializableDecoder implements SpecializedInterface
{
    use CanParseFilePath;

    /**
     * Return media (mime) type of the file at given file path
     */
    protected function getMediaTypeByFilePath(string $filepath): MediaType
    {
        $info = @getimagesize($this->parseFilePathOrFail($filepath));

        if (!is_array($info)) {
            // NEWEX
            throw new DecoderException('Failed to read media (MIME) type from data in file path');
        }

        try {
            return MediaType::from($info['mime']);
        } catch (ValueError) {
            // NEWEX
            throw new NotSupportedException('Unsupported media type (MIME) ' . $info['mime'] . '.');
        }
    }

    /**
     * Return media (mime) type of the given image data
     *
     * @throws DecoderException
     * @throws NotSupportedException
     */
    protected function getMediaTypeByBinary(string $data): MediaType
    {
        $info = @getimagesizefromstring($data);

        if (!is_array($info)) {
            // NEWEX
            throw new DecoderException('Failed to read media (MIME) type from binary data');
        }

        try {
            return MediaType::from($info['mime']);
        } catch (ValueError) {
            // NEWEX
            throw new NotSupportedException('Unsupported media type (MIME) ' . $info['mime'] . '.');
        }
    }
}
