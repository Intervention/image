<?php

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Drivers\DriverAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;

class HeightAnalyzer extends DriverAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return imagesy($image->core()->native());
    }
}
