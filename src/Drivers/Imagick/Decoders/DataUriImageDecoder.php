<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\DataUri;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class DataUriImageDecoder extends BinaryImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        $input = ($input instanceof DataUri) ? (string) $input : $input;

        if (!is_string($input)) {
            throw new InvalidArgumentException('Data Uri must be of type string or ' . DataUri::class);
        }

        $data = DataUri::decode($input)->data();

        return parent::decode($data);
    }
}
