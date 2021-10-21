<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Interfaces\FactoryInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ImageFactory implements FactoryInterface
{
    public function newImage(int $width, int $height): ImageInterface
    {
        $gd = imagecreatetruecolor($width, $height);

        return new Image(new Collection([
            new Frame($gd)
        ]));
    }
}
