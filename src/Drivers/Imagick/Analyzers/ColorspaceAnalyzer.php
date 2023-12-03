<?php

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Imagick;
use Intervention\Image\Drivers\DriverSpecializedAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;

class ColorspaceAnalyzer extends DriverSpecializedAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return match ($image->core()->native()->getImageColorspace()) {
            Imagick::COLORSPACE_CMYK => new CmykColorspace(),
            default => new RgbColorspace(),
        };
    }
}
