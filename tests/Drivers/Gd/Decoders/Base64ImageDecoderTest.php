<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\Base64ImageDecoder;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

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
