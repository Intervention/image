<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\DataUriImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Decoders\DataUriImageDecoder
 */
class DataUriImageDecoderTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testDecode(): void
    {
        $decoder = new DataUriImageDecoder();
        $result = $decoder->decode(
            sprintf('data:image/jpeg;base64,%s', base64_encode($this->getTestImageData('blue.gif')))
        );
        $this->assertInstanceOf(Image::class, $result);
    }
}
