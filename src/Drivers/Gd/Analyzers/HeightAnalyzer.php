<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\HeightAnalyzer as GenericHeightAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Traits\IsDriverSpecialized;

class HeightAnalyzer extends GenericHeightAnalyzer implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function analyze(ImageInterface $image): mixed
    {
        return imagesy($image->core()->native());
    }
}
