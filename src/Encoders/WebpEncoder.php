<?php

namespace Intervention\Image\Encoders;

class WebpEncoder extends AbstractEncoder
{
    public function __construct(public int $quality = 80)
    {
    }
}
