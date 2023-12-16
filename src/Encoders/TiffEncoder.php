<?php

namespace Intervention\Image\Encoders;

class TiffEncoder extends AbstractEncoder
{
    public function __construct(public int $quality = 75)
    {
    }
}
