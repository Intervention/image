<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class DataUriImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $uri = $this->parseDataUri($input);

        if (!$uri->isValid()) {
            throw new DecoderException('Unable to decode input');
        }

        if ($uri->isBase64Encoded()) {
            return parent::decode(base64_decode($uri->data()));
        }

        return parent::decode(urldecode($uri->data()));
    }
}
