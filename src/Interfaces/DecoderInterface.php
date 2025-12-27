<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DecoderInterface
{
    /**
     * Determine if the given input is supported by decoder
     *
     * Support does not mean that the input can necessarily be decrypted, but only
     * that the input might match the decoder and that it is worth trying.
     */
    public function supports(mixed $input): bool;

    /**
     * Decode given input either to color or image
     */
    public function decode(mixed $input): ImageInterface|ColorInterface;
}
