<?php

namespace Intervention\Image\Encoders;

class AvifEncoder extends AbstractEncoder
{
    public function __construct(public int $quality = 80)
    {
    }
}
