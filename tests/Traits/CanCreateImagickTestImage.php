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
        return $this->createWithImageDecoder()->handle(
            sprintf('%s/../images/%s', __DIR__, $filename)
        );
    }

    protected function createWithImageDecoder(): FilePathImageDecoder
    {
        return new FilePathImageDecoder();
    }
}
