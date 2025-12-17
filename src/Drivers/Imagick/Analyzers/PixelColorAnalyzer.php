<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Imagick;
use ImagickException;
use Intervention\Image\Analyzers\PixelColorAnalyzer as GenericPixelColorAnalyzer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PixelColorAnalyzer extends GenericPixelColorAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return $this->colorAt(
            $image->colorspace(),
            $image->core()->frame($this->frame_key)->native()
        );
    }

    protected function colorAt(ColorspaceInterface $colorspace, Imagick $imagick): ColorInterface
    {
        try {
            return $this->driver()
                ->colorProcessor($colorspace)
                ->nativeToColor(
                    $imagick->getImagePixelColor($this->x, $this->y)
                );
        } catch (ImagickException $e) {
            throw new AnalyzerException(
                'Failed to read color at position (' . $this->x . ', ' . $this->y . ')',
                previous: $e
            );
        }
    }
}
