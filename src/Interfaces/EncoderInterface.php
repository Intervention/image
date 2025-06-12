<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface EncoderInterface
{
    /**
     * Encode given image
     *
     * @throws RuntimeException
     */
    public function encode(ImageInterface $image): EncodedImageInterface;
}
