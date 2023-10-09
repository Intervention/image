<?php

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    public function transformColor(ColorInterface $color): ColorInterface
    {
        return match ($color) {
            CmykColor::class => $this->convertCmykColor($color),
            default => $color,
        };
    }

    protected function convertCmykColor(CmykColor $color): Color
    {
        return new Color(
            (int) (255 * (1 - $color->cyan()->value()) * (1 - $color->key()->value())),
            (int) (255 * (1 - $color->magenta()->value()) * (1 - $color->key()->value())),
            (int) (255 * (1 - $color->yellow()->value()) * (1 - $color->key()->value())),
        );
    }
}
