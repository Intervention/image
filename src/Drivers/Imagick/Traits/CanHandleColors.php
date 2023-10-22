<?php

namespace Intervention\Image\Drivers\Imagick\Traits;

use Imagick;
use ImagickPixel;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
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
    public function pixelToColor(ImagickPixel $pixel, ColorspaceInterface $colorspace): ColorInterface
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
     * Transforms given color to the corresponding ImagickPixel in given colorspace
     *
     * Colorspaces of color might be different from given colorspace. You might
     * have a CMYK Image but give an rgb color to a method. This makes sure
     * that the color is converted to the given colorspace. In this case
     * the image colorspace which is passed to this method.
     *
     * @param ColorInterface $color
     * @param ColorspaceInterface $colorspace
     * @return ImagickPixel
     */
    public function colorToPixel(ColorInterface $color, ColorspaceInterface $colorspace): ImagickPixel
    {
        $pixel = new ImagickPixel();
        $color = $color->convertTo($colorspace);

        switch (get_class($color)) {
            case CmykColor::class:
                $pixel->setColorValue(Imagick::COLOR_CYAN, $color->channel(Cyan::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_MAGENTA, $color->channel(Magenta::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_YELLOW, $color->channel(Yellow::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_BLACK, $color->channel(Key::class)->normalize());
                break;

            case RgbColor::class:
                $pixel->setColorValue(Imagick::COLOR_RED, $color->channel(Red::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_GREEN, $color->channel(Green::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_BLUE, $color->channel(Blue::class)->normalize());
                $pixel->setColorValue(Imagick::COLOR_ALPHA, $color->channel(Alpha::class)->normalize());
                break;
        }

        return $pixel;
    }
}
