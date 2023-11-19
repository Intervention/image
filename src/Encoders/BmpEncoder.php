<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class BmpEncoder implements EncoderInterface
{
    public function __construct(public int $color_limit = 0)
    {
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        return $image->encode($this);
    }
}
