<?php

namespace Intervention\Image\Encoders;

class BmpEncoder extends AbstractEncoder
{
    public function __construct(public int $color_limit = 0)
    {
    }
}
