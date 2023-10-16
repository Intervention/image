<?php

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    public function convertColor(ColorInterface $color): ColorInterface
    {
        return match (get_class($color)) {
            CmykColor::class => $this->convertCmykColor($color),
            default => $color,
        };
    }

    protected function convertCmykColor(CmykColor $color): Color
    {
        return new Color(
            (int) (255 * (1 - $color->cyan()->normalize()) * (1 - $color->key()->normalize())),
            (int) (255 * (1 - $color->magenta()->normalize()) * (1 - $color->key()->normalize())),
            (int) (255 * (1 - $color->yellow()->normalize()) * (1 - $color->key()->normalize())),
        );
    }
}
