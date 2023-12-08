<?php

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Drivers\DriverSpecializedAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;

class WidthAnalyzer extends DriverSpecializedAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return imagesx($image->core()->native());
    }
}
