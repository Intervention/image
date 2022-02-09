<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Decoders\ImageObjectDecoder;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 */
class ImageObjectDecoderTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testDecode(): void
    {
        $decoder = new ImageObjectDecoder();
        $result = $decoder->decode($this->createTestImage('blue.gif'));
        $this->assertInstanceOf(Image::class, $result);
    }
}
