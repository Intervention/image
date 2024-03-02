<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\DecoderException;

interface DecoderInterface
{
    /**
     * Decode given input either to color or image
     *
     * @param mixed $input
     * @return ImageInterface|ColorInterface
     * @throws DecoderException
     */
    public function decode(mixed $input): ImageInterface|ColorInterface;
}
