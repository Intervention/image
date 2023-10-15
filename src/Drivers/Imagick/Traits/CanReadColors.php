<?php

namespace Intervention\Image\Drivers\Imagick\Traits;

use ImagickPixel;
use Intervention\Image\Interfaces\ColorInterface;

trait CanReadColors
{
    public function colorToPixel(ColorInterface $color): ImagickPixel
    {
        return new ImagickPixel($color->toString());
    }
}
