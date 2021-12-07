<?php

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use IteratorAggregate;

class Image extends AbstractImage implements ImageInterface, IteratorAggregate
{
    public function getWidth(): int
    {
        return imagesx($this->getFrame()->getCore());
    }

    public function getHeight(): int
    {
        return imagesy($this->getFrame()->getCore());
    }

    public function pickColor(int $x, int $y, int $frame_key = 0): ?ColorInterface
    {
        if ($frame = $this->getFrame($frame_key)) {
            return new Color(imagecolorat($frame->getCore(), $x, $y));
        }

        return null;
    }
}
