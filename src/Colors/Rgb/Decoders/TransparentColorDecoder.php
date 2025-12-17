<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;

class TransparentColorDecoder extends HexColorDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new InvalidArgumentException('Input must be of type string');
        }

        if (strtolower($input) !== 'transparent') {
            throw new InvalidArgumentException('Input must be "transparent"');
        }

        return parent::decode('#ffffff00');
    }
}
