<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Analyzers;

use Intervention\Image\Analyzers\WidthAnalyzer as GenericWidthAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class WidthAnalyzer extends GenericWidthAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return imagesx($image->core()->native());
    }
}
