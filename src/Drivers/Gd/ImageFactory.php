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
        $color = imagecolorallocatealpha($gd, 0, 0, 0, 127);
        imagefill($gd, 0, 0, $color);
        imagesavealpha($gd, true);

        return new Image(new Collection([
            new Frame($gd)
        ]));
    }
}
