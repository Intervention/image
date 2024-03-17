<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\ResolutionAnalyzer as GenericResolutionAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Resolution;

class ResolutionAnalyzer extends GenericResolutionAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return new Resolution(...$image->core()->native()->getImageResolution());
    }
}
