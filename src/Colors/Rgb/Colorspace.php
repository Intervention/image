<?php

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    public function convertColor(ColorInterface $color): ColorInterface
    {
        return match (get_class($color)) {
            default => $color,
        };
    }
}
