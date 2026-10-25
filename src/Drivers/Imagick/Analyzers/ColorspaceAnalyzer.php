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
        $colorspace = $image->core()->native()->getImageColorspace();

        switch ($colorspace) {
            case Imagick::COLORSPACE_CMYK:
                return new Cmyk();

            case Imagick::COLORSPACE_SRGB:
            case Imagick::COLORSPACE_RGB:
                return new Rgb();

            case Imagick::COLORSPACE_HSL:
                return new Hsl();

            case Imagick::COLORSPACE_HSV:
                return new Hsv();

            case $this->imagickColorspaceValue('COLORSPACE_OKLAB'):
                return new Oklab();

            case $this->imagickColorspaceValue('COLORSPACE_OKLCH'):
                return new Oklch();

            default:
                throw new AnalyzerException('Failed to analyze unknown colorspace');
        }
    }

    /**
     * COLORSPACE_OKLAB and COLORSPACE_OKLCH only exist in recent ImageMagick builds.
     */
    private function imagickColorspaceValue(string $name): int|false
    {
        $constantName = Imagick::class . '::' . $name;

        return defined($constantName) ? constant($constantName) : false;
    }
}
