<?php

namespace Intervention\Image\Interfaces;

interface DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface;
}
