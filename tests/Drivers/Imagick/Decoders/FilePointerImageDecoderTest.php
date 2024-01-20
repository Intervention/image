<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Decoders\FilePointerImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Decoders\FilePointerImageDecoder
 */
class FilePointerImageDecoderTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testDecode(): void
    {
        $decoder = new FilePointerImageDecoder();
        $fp = fopen($this->getTestImagePath('test.jpg'), 'r');
        $result = $decoder->decode($fp);
        $this->assertInstanceOf(Image::class, $result);
    }
}
