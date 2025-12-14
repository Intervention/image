<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DecoderInterface
{
    /**
     * Decode given input either to color or image
     */
    public function decode(mixed $input): ImageInterface|ColorInterface;
}
