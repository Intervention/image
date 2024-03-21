<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ImageObjectDecoder extends SpecializableDecoder
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_a($input, ImageInterface::class)) {
            throw new DecoderException('Unable to decode input');
        }

        return $input;
    }
}
