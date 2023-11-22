<?php

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\DriverAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;

class ColorspaceAnalyzer extends DriverAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return new Colorspace();
    }
}
