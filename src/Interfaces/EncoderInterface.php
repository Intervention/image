<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\EncodedImage;

interface EncoderInterface
{
    /**
     * Encode given image
     *
     * @param ImageInterface $image
     * @return EncodedImage
     */
    public function encode(ImageInterface $image): EncodedImage;
}
