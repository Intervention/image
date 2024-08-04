<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;

class EncodedImageObjectDecoder extends BinaryImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_a($input, EncodedImage::class)) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode($input->toString());
    }
}
