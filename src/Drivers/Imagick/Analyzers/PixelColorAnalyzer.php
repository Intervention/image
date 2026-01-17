<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use ImagickException;
use Intervention\Image\Analyzers\PixelColorAnalyzer as GenericPixelColorAnalyzer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PixelColorAnalyzer extends GenericPixelColorAnalyzer implements SpecializedInterface
{
    /**
     * @throws AnalyzerException
     * @throws StateException
     */
    public function analyze(ImageInterface $image): mixed
    {
        $colorProcessor = $this->driver()->colorProcessor($image);

        return $this->colorAt($colorProcessor, $image->core()->frame($this->frame));
    }

    protected function colorAt(ColorProcessorInterface $processor, FrameInterface $frame): ColorInterface
    {
        try {
            return $processor->nativeToColor(
                $frame->native()->getImagePixelColor($this->x, $this->y)
            );
        } catch (ImagickException $e) {
            throw new AnalyzerException(
                'Failed to read pixel color at position ' . $this->x . ', ' . $this->y,
                previous: $e
            );
        }
    }
}
