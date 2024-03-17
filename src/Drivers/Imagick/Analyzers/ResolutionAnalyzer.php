<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Resolution;

class ResolutionAnalyzer implements AnalyzerInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return new Resolution(...$image->core()->native()->getImageResolution());
    }
}
