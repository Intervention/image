<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface EncoderInterface
{
    /**
     * Encode given image
     *
     * @param ImageInterface $image
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function encode(ImageInterface $image): EncodedImageInterface;
}
