<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface AnalyzerInterface
{
    /**
     * Analyze given image and return the retrieved data.
     */
    public function analyze(ImageInterface $image): mixed;
}
