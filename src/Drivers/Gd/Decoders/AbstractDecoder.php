<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\AbstractDecoder as GenericAbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;

abstract class AbstractDecoder extends GenericAbstractDecoder
{
    /**
     * Return media (mime) type of the file at given file path
     *
     * @param string $filepath
     * @throws DecoderException
     * @return string
     */
    protected function getMediaTypeByFilePath(string $filepath): string
    {
        $info = @getimagesize($filepath);

        if (!is_array($info)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!array_key_exists('mime', $info)) {
            throw new DecoderException('Unable to decode input');
        }

        return $info['mime'];
    }

    /**
     * Return media (mime) type of the given image data
     *
     * @param string $data
     * @throws DecoderException
     * @return string
     */
    protected function getMediaTypeByBinary(string $data): string
    {
        $info = @getimagesizefromstring($data);

        if (!is_array($info)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!array_key_exists('mime', $info)) {
            throw new DecoderException('Unable to decode input');
        }

        return $info['mime'];
    }
}
