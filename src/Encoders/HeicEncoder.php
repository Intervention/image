<?php

namespace Intervention\Image\Encoders;

class HeicEncoder extends AbstractEncoder
{
    public function __construct(public int $quality = 75)
    {
    }
}
