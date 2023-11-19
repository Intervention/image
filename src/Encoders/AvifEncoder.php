<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AvifEncoder implements EncoderInterface
{
    public function __construct(public int $quality = 80)
    {
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        return $image->encode($this);
    }
}
