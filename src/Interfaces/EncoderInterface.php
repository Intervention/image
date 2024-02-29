<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\EncoderException;

interface EncoderInterface
{
    /**
     * Encode given image
     *
     * @param ImageInterface $image
     * @throws EncoderException
     * @return EncodedImageInterface
     */
    public function encode(ImageInterface $image): EncodedImageInterface;
}
