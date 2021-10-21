<?php

namespace Intervention\Image\Interfaces;

interface EncoderInterface
{
    public function encode(ImageInterface $image): string;
}
