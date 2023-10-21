<?php

namespace Intervention\Image\Drivers\Imagick\Traits;

use ImagickPixel;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Traits\CanHandleInput;

trait CanHandleColors
{
    use CanHandleInput;

    /**
     * Transforms ImagickPixel to own color object
     *
     * @param ImagickPixel $pixel
     * @return ColorInterface
     */
    public function colorFromPixel(ImagickPixel $pixel): ColorInterface
    {
        return $this->handleInput($pixel->getColorAsString());
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
