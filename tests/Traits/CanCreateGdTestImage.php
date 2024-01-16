<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Traits;

use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Image;

trait CanCreateGdTestImage
{
    public function readTestImage($filename = 'test.jpg'): Image
    {
        return (new FilePathImageDecoder())->handle(
            $this->getTestImagePath($filename)
        );
    }

    public function createTestImage(int $width, int $height): Image
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

    public function createTestAnimation(): Image
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
