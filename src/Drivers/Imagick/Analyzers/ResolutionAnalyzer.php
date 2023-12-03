<?php

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\DriverSpecializedAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Resolution;

class ResolutionAnalyzer extends DriverSpecializedAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return new Resolution(...$image->core()->native()->getImageResolution());
    }
}
