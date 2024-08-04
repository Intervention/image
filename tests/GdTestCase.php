<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Image;

abstract class GdTestCase extends BaseTestCase
{
    public static function readTestImage($filename = 'test.jpg'): Image
    {
        return (new Driver())->specialize(new FilePathImageDecoder())->decode(
            static::getTestResourcePath($filename)
        );
    }

    public static function createTestImage(int $width, int $height): Image
    {
        $gd = imagecreatetruecolor($width, $height);
        imagefill($gd, 0, 0, imagecolorallocate($gd, 255, 0, 0));

        return new Image(
            new Driver(),
            new Core([
                new Frame($gd)
            ])
        );
    }

    public static function createTestAnimation(): Image
    {
        $gd1 = imagecreatetruecolor(3, 2);
        imagefill($gd1, 0, 0, imagecolorallocate($gd1, 255, 0, 0));
        $gd2 = imagecreatetruecolor(3, 2);
        imagefill($gd2, 0, 0, imagecolorallocate($gd1, 0, 255, 0));
        $gd3 = imagecreatetruecolor(3, 2);
        imagefill($gd3, 0, 0, imagecolorallocate($gd1, 0, 0, 255));

        return new Image(
            new Driver(),
            new Core([
                new Frame($gd1),
                new Frame($gd2),
                new Frame($gd3),
            ])
        );
    }
}
