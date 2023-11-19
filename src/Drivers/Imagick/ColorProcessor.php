<?php

namespace Intervention\Image\Drivers\Imagick;

use ImagickPixel;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class ColorProcessor implements ColorProcessorInterface
{
    public function __construct(protected ColorspaceInterface $colorspace)
    {
    }

    public function colorToNative(ColorInterface $color): ImagickPixel
    {
        return new ImagickPixel(
            (string) $color->convertTo($this->colorspace)
        );
    }
}
