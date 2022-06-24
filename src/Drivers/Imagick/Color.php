<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Abstract\AbstractColor;
use Intervention\Image\Interfaces\ColorInterface;

class Color extends AbstractColor implements ColorInterface
{
    public function __construct(protected ImagickPixel $pixel)
    {
        //
    }

    public function getPixel(): ImagickPixel
    {
        return $this->pixel;
    }

    public function red(): int
    {
        return intval(round($this->pixel->getColorValue(Imagick::COLOR_RED) * 255));
    }

    public function green(): int
    {
        return intval(round($this->pixel->getColorValue(Imagick::COLOR_GREEN) * 255));
    }

    public function blue(): int
    {
        return intval(round($this->pixel->getColorValue(Imagick::COLOR_BLUE) * 255));
    }

    public function alpha(): float
    {
        return round($this->pixel->getColorValue(Imagick::COLOR_ALPHA), 2);
    }

    public function toArray(): array
    {
        return [
            $this->red(),
            $this->green(),
            $this->blue(),
            $this->alpha()
        ];
    }

    public function toInt(): int
    {
        $r = $this->red();
        $g = $this->green();
        $b = $this->blue();
        $a = intval(round($this->alpha() * 255));

        return intval(($a << 24) + ($r << 16) + ($g << 8) + $b);
    }
}
