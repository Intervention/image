<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface DecoderInterface
{
    /**
     * Decode given input either to color or image
     *
     * @param mixed $input
     * @throws RuntimeException
     * @return ImageInterface|ColorInterface
     */
    public function decode(mixed $input): ImageInterface|ColorInterface;
}
