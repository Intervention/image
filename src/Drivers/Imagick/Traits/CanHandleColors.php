<?php

namespace Intervention\Image\Drivers\Imagick\Traits;

use Imagick;
use ImagickPixel;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

trait CanHandleColors
{
    /**
     * Transforms ImagickPixel to own color object
     *
     * @param ImagickPixel        $pixel
     * @param ColorspaceInterface $colorspace
     * @return ColorInterface
     */
    public function colorFromPixel(ImagickPixel $pixel, ColorspaceInterface $colorspace): ColorInterface
    {
        return match (get_class($colorspace)) {
            CmykColorspace::class => $colorspace->colorFromNormalized([
                $pixel->getColorValue(Imagick::COLOR_CYAN),
                $pixel->getColorValue(Imagick::COLOR_MAGENTA),
                $pixel->getColorValue(Imagick::COLOR_YELLOW),
                $pixel->getColorValue(Imagick::COLOR_BLACK),
            ]),
            default => $colorspace->colorFromNormalized([
                $pixel->getColorValue(Imagick::COLOR_RED),
                $pixel->getColorValue(Imagick::COLOR_GREEN),
                $pixel->getColorValue(Imagick::COLOR_BLUE),
                $pixel->getColorValue(Imagick::COLOR_ALPHA),
            ]),
        };
    }

    /**
     * Transforms given color to the corresponding ImagickPixel
     *
     * @param ColorInterface $color
     * @return ImagickPixel
     */
    public function colorToPixel(ColorInterface $color): ImagickPixel
    {
        return new ImagickPixel($color->toString());
    }
}
