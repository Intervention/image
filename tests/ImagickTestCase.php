<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Imagick;
use ImagickPixel;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Image;

abstract class ImagickTestCase extends BaseTestCase
{
    public static function readTestImage($filename = 'test.jpg'): Image
    {
        return (new Driver())->specialize(new FilePathImageDecoder())->decode(
            static::getTestResourcePath($filename)
        );
    }

    public static function createTestImage(int $width, int $height): Image
    {
        $background = new ImagickPixel('rgb(255, 0, 0)');
        $imagick = new Imagick();
        $imagick->newImage($width, $height, $background, 'png');
        $imagick->setType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setColorspace(Imagick::COLORSPACE_SRGB);
        $imagick->setImageResolution(96, 96);

        return new Image(
            new Driver(),
            new Core($imagick)
        );
    }

    public static function createTestAnimation(): Image
    {
        $imagick = new Imagick();
        $imagick->setFormat('gif');

        for ($i = 0; $i < 3; $i++) {
            $frame = new Imagick();
            $frame->newImage(3, 2, new ImagickPixel('rgb(255, 0, 0)'), 'gif');
            $frame->setImageDelay(10);
            $imagick->addImage($frame);
        }

        return new Image(
            new Driver(),
            new Core($imagick)
        );
    }
}
