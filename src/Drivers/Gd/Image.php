<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
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
