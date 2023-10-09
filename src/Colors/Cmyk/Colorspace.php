<?php

namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    public function transformColor(ColorInterface $color): ColorInterface
    {
        return match (get_class($color)) {
            RgbColor::class => $this->convertRgbColor($color),
            default => $color,
        };
    }

    protected function convertRgbColor(RgbColor $color): CmykColor
    {
        $c = (255 - $color->red()->value()) / 255.0 * 100;
        $m = (255 - $color->green()->value()) / 255.0 * 100;
        $y = (255 - $color->blue()->value()) / 255.0 * 100;
        $k = intval(round(min([$c, $m, $y])));

        $c = intval(round($c - $k));
        $m = intval(round($m - $k));
        $y = intval(round($y - $k));

        return new CmykColor($c, $m, $y, $k);
    }
}
