<?php

namespace Intervention\Image\Analyzers;

use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class AbstractAnalyzer implements AnalyzerInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return $image->analyze($this);
    }
}
