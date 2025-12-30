<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use ImagickException;
use Intervention\Image\Analyzers\HeightAnalyzer as GenericHeightAnalyzer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class HeightAnalyzer extends GenericHeightAnalyzer implements SpecializedInterface
{
    /**
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): mixed
    {
        try {
            return $image->core()->native()->getImageHeight();
        } catch (ImagickException $e) {
            throw new AnalyzerException('Failed to read image height', previous: $e);
        }
    }
}
