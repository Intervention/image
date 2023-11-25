<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\Base64ImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Decoders\Base64ImageDecoder
 */
class Base64ImageDecoderTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testDecode(): void
    {
        $decoder = new Base64ImageDecoder();
        $result = $decoder->decode(
            base64_encode($this->getTestImageData('blue.gif'))
        );
        $this->assertInstanceOf(Image::class, $result);
    }
}
