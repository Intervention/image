<?php

namespace Intervention\Image\Traits;

use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Interfaces\SizeInterface;

trait CanResizeGeometrically
{
    public function resizeGeometrically(SizeInterface $size): Resizer
    {
        return new Resizer($size);
    }
}
