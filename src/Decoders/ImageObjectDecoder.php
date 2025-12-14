<?php

declare(strict_types=1);

namespace Intervention\Image\Decoders;

use http\Exception\InvalidArgumentException;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;

class ImageObjectDecoder extends AbstractDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_a($input, ImageInterface::class)) {
            // NEWEX
            throw new InvalidArgumentException('Input must be of type ' . ImageInterface::class);
        }

        return $input;
    }
}
