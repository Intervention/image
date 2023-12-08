<?php

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\DriverSpecializedAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;

class ColorspaceAnalyzer extends DriverSpecializedAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return new Colorspace();
    }
}
