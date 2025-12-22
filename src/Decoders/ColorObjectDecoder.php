<?php

declare(strict_types=1);

namespace Intervention\Image\Decoders;

use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;

class ColorObjectDecoder extends AbstractDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $input instanceof ColorInterface;
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ColorInterface
    {
        if (!is_a($input, ColorInterface::class)) {
            throw new InvalidArgumentException('Color object must be of type ' . ColorInterface::class);
        }

        return $input;
    }
}
