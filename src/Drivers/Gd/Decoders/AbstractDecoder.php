<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\MediaType;

abstract class AbstractDecoder extends SpecializableDecoder implements SpecializedInterface
{
    /**
     * Return media (mime) type of the file at given file path
     *
     * @param string $filepath
     * @throws DecoderException
     * @return MediaType
     */
    protected function getMediaTypeByFilePath(string $filepath): MediaType
    {
        $info = @getimagesize($filepath);

        if (!is_array($info)) {
            throw new DecoderException('Unable to detect media (MIME) from data in file path.');
        }

        if (!array_key_exists('mime', $info)) {
            throw new DecoderException('Unable to detect media (MIME) from data in file path.');
        }

        return MediaType::from($info['mime']);
    }

    /**
     * Return media (mime) type of the given image data
     *
     * @param string $data
     * @throws DecoderException
     * @return MediaType
     */
    protected function getMediaTypeByBinary(string $data): MediaType
    {
        $info = @getimagesizefromstring($data);

        if (!is_array($info)) {
            throw new DecoderException('Unable to detect media (MIME) from binary data.');
        }

        if (!array_key_exists('mime', $info)) {
            throw new DecoderException('Unable to detect media (MIME) from binary data.');
        }

        return MediaType::from($info['mime']);
    }
}
