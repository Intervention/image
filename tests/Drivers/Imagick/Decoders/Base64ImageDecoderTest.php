<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Decoders\Base64ImageDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Decoders\Base64ImageDecoder
 */
class Base64ImageDecoderTest extends TestCase
{
    protected Base64ImageDecoder $decoder;

    public function setUp(): void
    {
        $this->decoder = new Base64ImageDecoder();
    }

    public function testDecode(): void
    {
        $result = $this->decoder->decode(
            base64_encode($this->getTestImageData('blue.gif'))
        );

        $this->assertInstanceOf(Image::class, $result);
    }

    public function testDecoderInvalid(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode('test');
    }
}
