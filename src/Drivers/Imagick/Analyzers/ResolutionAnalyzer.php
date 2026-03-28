<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use ImagickException;
use Intervention\Image\Analyzers\ResolutionAnalyzer as GenericResolutionAnalyzer;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Length;
use Intervention\Image\Resolution;

class ResolutionAnalyzer extends GenericResolutionAnalyzer implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): mixed
    {
        $imagick = $image->core()->native();

        try {
            $imageResolution = $imagick->getImageResolution();
        } catch (ImagickException $e) {
            throw new AnalyzerException('Failed to read image resolution', previous: $e);
        }

        return new Resolution(
            $imageResolution['x'],
            $imageResolution['y'],
            $imagick->getImageUnits() === 2 ? Length::CM : Length::INCH
        );
    }
}
