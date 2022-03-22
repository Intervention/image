<?php

namespace Intervention\Image\Tests\Traits;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;

trait CanCreateGdTestImage
{
    public function getTestImagePath($filename = 'test.jpg'): string
    {
        return sprintf('%s/../images/%s', __DIR__, $filename);
    }

    public function getTestImageData($filename = 'test.jpg'): string
    {
        return file_get_contents($this->getTestImagePath($filename));
    }

    public function createTestImage($filename = 'test.jpg'): Image
    {
        return $this->createWithImageDecoder()->handle(
            $this->getTestImagePath($filename)
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
        return new Image(new Collection([
            new Frame($gd1),
            new Frame($gd2),
            new Frame($gd3),
        ]));
    }

    protected function createWithImageDecoder(): FilePathImageDecoder
    {
        return new FilePathImageDecoder();
    }
}
