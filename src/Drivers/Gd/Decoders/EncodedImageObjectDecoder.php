<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use http\Exception\InvalidArgumentException;
use Intervention\Image\EncodedImage;
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
            // NEWEX
            throw new InvalidArgumentException('Input must be of type ' . EncodedImage::class);
        }

        return parent::decode($input->toString());
    }
}
