<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class AbstractEncoder implements EncoderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        return $image->encode($this);
    }
}
