<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\ResolutionAnalyzer as GenericResolutionAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Resolution;

class ResolutionAnalyzer extends GenericResolutionAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return new Resolution(...imageresolution($image->core()->native()));
    }
}
