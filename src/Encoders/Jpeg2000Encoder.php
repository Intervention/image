<?php

namespace Intervention\Image\Encoders;

class Jpeg2000Encoder extends AbstractEncoder
{
    public function __construct(public int $quality = 75)
    {
    }
}
