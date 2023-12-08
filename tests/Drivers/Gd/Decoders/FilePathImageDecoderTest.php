<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder
 */
class FilePathImageDecoderTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testDecode(): void
    {
        $decoder = new FilePathImageDecoder();
        $result = $decoder->decode(
            $this->getTestImagePath()
        );
        $this->assertInstanceOf(Image::class, $result);
    }
}
