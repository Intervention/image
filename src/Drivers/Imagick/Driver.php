<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;

class Driver extends AbstractDriver
{
    public function createImage(int $width, int $height): ImageInterface
    {
        $background = new ImagickPixel('rgba(0, 0, 0, 0)');

        $imagick = new Imagick();
        $imagick->newImage($width, $height, $background, 'png');
        $imagick->setType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setColorspace(Imagick::COLORSPACE_SRGB);
        $imagick->setImageResolution(96, 96);

        return new Image($this, new Core($imagick));
    }

    public function handleInput(mixed $input): ImageInterface|ColorInterface
    {
        return (new InputHandler())->handle($input);
    }

    public function colorToNative(ColorInterface $color, ColorspaceInterface $colorspace): mixed
    {
        return (new ColorProcessor($colorspace))->colorToNative($color);
    }
}
