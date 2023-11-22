<?php

namespace Intervention\Image\Interfaces;

interface AnalyzerInterface
{
    public function analyze(ImageInterface $image): mixed;
}
