<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use IteratorAggregate;

class Image extends AbstractImage implements ImageInterface, IteratorAggregate
{
    public function width(): int
    {
        return $this->frames->first()->getCore()->getImageWidth();
    }

    public function height(): int
    {
        return $this->frames->first()->getCore()->getImageHeight();
    }

    public function pickColor(int $x, int $y, int $frame_key = 0): ?ColorInterface
    {
        if ($frame = $this->getFrame($frame_key)) {
            return new Color($frame->getCore()->getImagePixelColor($x, $y));
        }

        return null;
    }
}
