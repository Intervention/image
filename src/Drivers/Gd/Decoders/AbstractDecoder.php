<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
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
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws NotSupportedException
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     */
    protected function getMediaTypeByFilePath(string $filepath): MediaType
    {
        $filepath = $this->readableFilePathOrFail($filepath);

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
            throw new ImageDecoderException('Failed to read media (MIME) type from data in file path');
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
     * @throws ImageDecoderException
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
            throw new ImageDecoderException('Failed to read media (MIME) type from binary data');
        }

        try {
            return MediaType::from($info['mime']);
        } catch (ValueError) {
            throw new NotSupportedException('Unsupported media type (MIME) ' . $info['mime'] . '.');
        }
    }
}
