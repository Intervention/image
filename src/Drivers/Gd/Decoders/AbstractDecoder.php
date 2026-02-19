<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\MediaType;
use ValueError;

abstract class AbstractDecoder extends SpecializableDecoder implements SpecializedInterface
{
    /**
     * Return media (mime) type of the file at given file path
     *
     * @throws DecoderException
     * @throws NotSupportedException
     */
    protected function getMediaTypeByFilePath(string $filepath): MediaType
    {
        if (function_exists('finfo_file') && function_exists('finfo_open')) {
            $mediaType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filepath);
            if (is_string($mediaType)) {
                try {
                    return MediaType::from($mediaType);
                } catch (ValueError) {
                    throw new NotSupportedException('Unsupported media type (MIME) ' . $mediaType . '.');
                }
            }
        }

        $info = @getimagesize($filepath);

        if (!is_array($info)) {
            throw new DecoderException('Unable to detect media (MIME) from data in file path.');
        }

        try {
            return MediaType::from($info['mime']);
        } catch (ValueError) {
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
        if (function_exists('finfo_buffer') && function_exists('finfo_open')) {
            $mediaType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);
            if (is_string($mediaType)) {
                try {
                    return MediaType::from($mediaType);
                } catch (ValueError) {
                    throw new NotSupportedException('Unsupported media type (MIME) ' . $mediaType . '.');
                }
            }
        }

        $info = @getimagesizefromstring($data);

        if (!is_array($info)) {
            throw new DecoderException('Unable to detect media (MIME) from binary data.');
        }

        try {
            return MediaType::from($info['mime']);
        } catch (ValueError) {
            throw new NotSupportedException('Unsupported media type (MIME) ' . $info['mime'] . '.');
        }
    }
}
