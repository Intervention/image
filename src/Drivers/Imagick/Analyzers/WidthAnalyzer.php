<?php

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\DriverSpecializedAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;

class WidthAnalyzer extends DriverSpecializedAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return $image->core()->native()->getImageWidth();
    }
}
