<?php

namespace Intervention\Image\Encoders;

class JpegEncoder extends AbstractEncoder
{
    public function __construct(public int $quality = 80)
    {
    }
}
