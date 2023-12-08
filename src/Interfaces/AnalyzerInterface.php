<?php

namespace Intervention\Image\Interfaces;

interface AnalyzerInterface
{
    /**
     * Analyze given image and return the retrieved data
     *
     * @param ImageInterface $image
     * @return mixed
     */
    public function analyze(ImageInterface $image): mixed;
}
