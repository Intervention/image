<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface AnalyzerInterface
{
    /**
     * Analyze given image and return the retrieved data
     *
     * @throws RuntimeException
     */
    public function analyze(ImageInterface $image): mixed;
}
