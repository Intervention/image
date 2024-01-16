<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ImageInterface;

class WidthAnalyzer extends DriverSpecialized implements AnalyzerInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return $image->core()->native()->getImageWidth();
    }
}
