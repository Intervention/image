<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Drivers\Specializable;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class SpecializableAnalyzer extends Specializable implements AnalyzerInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        return $image->analyze($this);
    }
}
