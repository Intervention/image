<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Interfaces\FactoryInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ImageFactory implements FactoryInterface
{
    public function newImage(int $width, int $height): ImageInterface
    {
        return new Image(
            new Collection([
                new Frame($this->newCore($width, $height))
            ])
        );
    }

    public function newCore(int $width, int $height)
    {
        $core = imagecreatetruecolor($width, $height);
        $color = imagecolorallocatealpha($core, 0, 0, 0, 127);
        imagefill($core, 0, 0, $color);
        imagesavealpha($core, true);

        return $core;
    }
}
