<?php

declare(strict_types=1);

namespace Intervention\Image\Analyzers;

use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializableInterface;

abstract class SpecializableAnalyzer implements AnalyzerInterface, SpecializableInterface
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
