<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface EncoderInterface
{
    /**
     * Encode given image
     */
    public function encode(ImageInterface $image): EncodedImageInterface;

    /**
     * Set encoder options
     */
    public function setOptions(mixed ...$options): self;
}
