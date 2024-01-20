<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface EncoderInterface
{
    /**
     * Encode given image
     *
     * @param ImageInterface $image
     * @return EncodedImageInterface
     */
    public function encode(ImageInterface $image): EncodedImageInterface;
}
