<?php

namespace Intervention\Image\Drivers\Imagick;

use ImagickPixel;
use Intervention\Image\Colors\Parser;
use Intervention\Image\Interfaces\ColorInterface;

class ColorTransformer
{
    /**
     * Transforms ImagickPixel to own color object
     *
     * @param int $value
     * @return ColorInterface
     */
    public static function colorFromPixel(ImagickPixel $pixel): ColorInterface
    {
        return Parser::parse($pixel->getColorAsString());
    }

    /**
     * Transforms given color to the corresponding ImagickPixel
     *
     * @param ColorInterface $color
     * @return ImagickPixel
     */
    public static function colorToPixel(ColorInterface $color): ImagickPixel
    {
        return new ImagickPixel($color->toString());
    }
}
