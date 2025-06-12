<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class ColorProcessor implements ColorProcessorInterface
{
    public function __construct(protected ColorspaceInterface $colorspace)
    {
        //
    }

    public function colorToNative(ColorInterface $color): ImagickPixel
    {
        return new ImagickPixel(
            (string) $color->convertTo($this->colorspace)
        );
    }

    public function nativeToColor(mixed $native): ColorInterface
    {
        return match ($this->colorspace::class) {
            CmykColorspace::class => $this->colorspace->colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_CYAN),
                $native->getColorValue(Imagick::COLOR_MAGENTA),
                $native->getColorValue(Imagick::COLOR_YELLOW),
                $native->getColorValue(Imagick::COLOR_BLACK),
            ]),
            default => $this->colorspace->colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_RED),
                $native->getColorValue(Imagick::COLOR_GREEN),
                $native->getColorValue(Imagick::COLOR_BLUE),
                $native->getColorValue(Imagick::COLOR_ALPHA),
            ]),
        };
    }
}
