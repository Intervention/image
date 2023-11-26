<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;

class Driver extends AbstractDriver
{
    public function id(): string
    {
        return 'GD';
    }

    public function createImage(int $width, int $height): ImageInterface
    {
        // build new transparent GDImage
        $data = imagecreatetruecolor($width, $height);
        imagesavealpha($data, true);
        $background = imagecolorallocatealpha($data, 255, 0, 255, 127);
        imagealphablending($data, false);
        imagefill($data, 0, 0, $background);
        imagecolortransparent($data, $background);

        return new Image(
            $this,
            new Core([
                new Frame($data)
            ])
        );
    }

    public function handleInput(mixed $input): ImageInterface|ColorInterface
    {
        return (new InputHandler())->handle($input);
    }

    public function colorProcessor(ColorspaceInterface $colorspace): ColorProcessorInterface
    {
        return new ColorProcessor($colorspace);
    }
}
