<?php

namespace Intervention\Image\Tests\Traits;

use Intervention\Image\Drivers\Imagick\Decoders\FilePathImageDecoder;
use Intervention\Image\Image;

trait CanCreateImagickTestImage
{
    public function readTestImage($filename = 'test.jpg'): Image
    {
        return (new FilePathImageDecoder())->handle(
            sprintf('%s/../images/%s', __DIR__, $filename)
        );
    }
}
