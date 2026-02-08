<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class Base64ImageDecoder extends BinaryImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!$this->isValidBase64($input)) {
            throw new DecoderException('Unable to decode input. Expected a valid base64 encoded string.');
        }

        return parent::decode(base64_decode((string) $input));
    }
}
