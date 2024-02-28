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
     * @return EncodedImageInterface
     * @throws EncoderException
     */
    public function encode(ImageInterface $image): EncodedImageInterface;
}
