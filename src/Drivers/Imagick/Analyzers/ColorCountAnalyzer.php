<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use ImagickException;
use Intervention\Image\Analyzers\ColorCountAnalyzer as GenericColorCountAnalyzer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Interfaces\ImageInterface;

class ColorCountAnalyzer extends GenericColorCountAnalyzer
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     *
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): ?int
    {
        try {
            return $image->core()->native()->getImageColors();
        } catch (ImagickException $e) {
            throw new AnalyzerException('Unable to determine color count', previous: $e);
        }
    }
}
