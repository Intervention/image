<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Error;
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
        try {
            $colorspace = $image->core()->native()->getImageColorspace();

            // OKLAB/OKLCH only exist on recent ImageMagick builds, so the
            // constants are resolved through defined()/constant() (mirroring the
            // ColorspaceModifier) to avoid referencing them where they are
            // undefined. Any unexpected resolution error is normalized below.
            return match (true) {
                $colorspace === Imagick::COLORSPACE_CMYK => new Cmyk(),
                $colorspace === Imagick::COLORSPACE_SRGB,
                $colorspace === Imagick::COLORSPACE_RGB => new Rgb(),
                $colorspace === Imagick::COLORSPACE_HSL => new Hsl(),
                $colorspace === Imagick::COLORSPACE_HSB => new Hsv(),
                defined(Imagick::class . '::COLORSPACE_OKLAB')
                    && $colorspace === constant(Imagick::class . '::COLORSPACE_OKLAB') => new Oklab(),
                defined(Imagick::class . '::COLORSPACE_OKLCH')
                    && $colorspace === constant(Imagick::class . '::COLORSPACE_OKLCH') => new Oklch(),
                default => throw new AnalyzerException('Failed to analyze unknown colorspace'),
            };
        } catch (Error $e) {
            throw new AnalyzerException('Failed to analyze colorspace', previous: $e);
        }
    }
}
