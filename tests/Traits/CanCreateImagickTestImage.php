<?php

namespace Intervention\Image\Tests\Traits;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Imagick\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Drivers\Imagick\Image;

trait CanCreateImagickTestImage
{
    public function createTestImage($filename = 'test.jpg'): Image
    {
        return $this->testImageDecoder()->handle(
            sprintf('%s/../images/%s', __DIR__, $filename)
        );
    }

    protected function testImageDecoder(): FilePathImageDecoder
    {
        return new FilePathImageDecoder();
    }
}
