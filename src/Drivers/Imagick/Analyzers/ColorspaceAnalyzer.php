<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Imagick;
use Intervention\Image\Analyzers\ColorspaceAnalyzer as GenericColorspaceAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
use Intervention\Image\Colors\Hsv\Colorspace as Hsv;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Interfaces\SpecializedInterface;

class ColorspaceAnalyzer extends GenericColorspaceAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return match ($image->core()->native()->getImageColorspace()) {
            Imagick::COLORSPACE_CMYK => new Cmyk(),
            Imagick::COLORSPACE_SRGB, Imagick::COLORSPACE_RGB => new Rgb(),
            Imagick::COLORSPACE_HSL => new Hsl(),
            Imagick::COLORSPACE_HSB => new Hsv(),
            default => throw new DriverException('Failed to analyze unknown colorspace'),
        };
    }
}
