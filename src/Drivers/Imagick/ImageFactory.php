<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Interfaces\FactoryInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ImageFactory implements FactoryInterface
{
    public function newImage(int $width, int $height): ImageInterface
    {
        return new Image($this->newCore($width, $height));
    }

    public function newCore(int $width, int $height)
    {
        $imagick = new Imagick();
        $imagick->newImage($width, $height, new ImagickPixel('rgba(0, 0, 0, 0)'), 'png');
        $imagick->setType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setColorspace(Imagick::COLORSPACE_UNDEFINED);

        return $imagick;
    }
}
