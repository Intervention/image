<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ColorspaceAnalyzer extends DriverSpecialized implements AnalyzerInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return new Colorspace();
    }
}
