<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Imagick;
use Intervention\Image\Analyzers\ColorspaceAnalyzer as GenericColorspaceAnalyzer;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
use Intervention\Image\Colors\Hsv\Colorspace as Hsv;
use Intervention\Image\Colors\Oklab\Colorspace as Oklab;
use Intervention\Image\Colors\Oklch\Colorspace as Oklch;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class ColorspaceAnalyzer extends GenericColorspaceAnalyzer implements SpecializedInterface
{
    /**
     * @throws AnalyzerException
     */
    public function analyze(ImageInterface $image): mixed
    {
        return match ($image->core()->native()->getImageColorspace()) {
            Imagick::COLORSPACE_CMYK => new Cmyk(),
            Imagick::COLORSPACE_SRGB, Imagick::COLORSPACE_RGB => new Rgb(),
            Imagick::COLORSPACE_HSL => new Hsl(),
            Imagick::COLORSPACE_HSB => new Hsv(),
            constant(Imagick::class . '::COLORSPACE_OKLAB') => new Oklab(),
            constant(Imagick::class . '::COLORSPACE_OKLCH') => new Oklch(),
            default => throw new AnalyzerException('Failed to analyze unknown colorspace'),
        };
    }
}
