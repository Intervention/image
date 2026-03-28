<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use ImagickException;
use Intervention\Image\Analyzers\WidthAnalyzer as GenericWidthAnalyzer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class WidthAnalyzer extends GenericWidthAnalyzer implements SpecializedInterface
{
    /**
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): mixed
    {
        try {
            return $image->core()->native()->getImageWidth();
        } catch (ImagickException $e) {
            throw new AnalyzerException('Failed to read image width', previous: $e);
        }
    }
}
