<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\ImageObjectDecoder;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @covers \Intervention\Image\Drivers\Gd\Decoders\ImageObjectDecoder
 */
class ImageObjectDecoderTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testDecode(): void
    {
        $decoder = new ImageObjectDecoder();
        $result = $decoder->decode($this->createTestImage('blue.gif'));
        $this->assertInstanceOf(Image::class, $result);
    }
}
