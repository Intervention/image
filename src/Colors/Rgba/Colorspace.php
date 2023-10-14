<?php

namespace Intervention\Image\Colors\Rgba;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    /**
     * {@inheritdoc}
     */
    public function convertColor(ColorInterface $color): ColorInterface
    {
        return match (get_class($color)) {
            CmykColor::class => $this->convertCmykColor($color),
            RgbColor::class => $this->convertRgbColor($color),
            default => $color,
        };
    }

    /**
     * Convert given color to the RGBA colorspace
     *
     * @param RgbColor $color
     * @return Color
     */
    protected function convertRgbColor(RgbColor $color): Color
    {
        return new Color(
            $color->red()->value(),
            $color->green()->value(),
            $color->blue()->value(),
            255
        );
    }

    /**
     * Convert given color to the RGBA colorspace
     *
     * @param CmykColor $color
     * @return Color
     */
    protected function convertCmykColor(CmykColor $color): Color
    {
        return new Color(
            (int) (255 * (1 - $color->cyan()->normalize()) * (1 - $color->key()->normalize())),
            (int) (255 * (1 - $color->magenta()->normalize()) * (1 - $color->key()->normalize())),
            (int) (255 * (1 - $color->yellow()->normalize()) * (1 - $color->key()->normalize())),
            255
        );
    }
}
