<?php

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\DriverAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;

class WidthAnalyzer extends DriverAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return $image->core()->native()->getImageWidth();
    }
}
