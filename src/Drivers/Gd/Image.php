<?php

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use IteratorAggregate;

class Image extends AbstractImage implements ImageInterface, IteratorAggregate
{
    public function width(): int
    {
        return imagesx($this->frames->first()->getCore());
    }

    public function height(): int
    {
        return imagesy($this->frames->first()->getCore());
    }
}
