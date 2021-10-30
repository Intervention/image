<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\EncodedImage;

interface EncoderInterface
{
    public function encode(ImageInterface $image): EncodedImage;
}
