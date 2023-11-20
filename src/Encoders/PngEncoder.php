<?php

namespace Intervention\Image\Encoders;

class PngEncoder extends AbstractEncoder
{
    public function __construct(public int $color_limit = 0)
    {
    }
}
