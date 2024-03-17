<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\WidthAnalyzer as GenricWidthAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Traits\IsDriverSpecialized;

class WidthAnalyzer extends GenricWidthAnalyzer implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function analyze(ImageInterface $image): mixed
    {
        return imagesx($image->core()->native());
    }
}
