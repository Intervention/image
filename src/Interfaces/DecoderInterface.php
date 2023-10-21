<?php

namespace Intervention\Image\Interfaces;

interface DecoderInterface
{
    /**
     * Decode given input either to color or image
     *
     * @param mixed $input
     * @return ImageInterface|ColorInterface
     */
    public function decode($input): ImageInterface|ColorInterface;
}
