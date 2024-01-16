<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Decoders\DataUriImageDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use stdClass;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Decoders\DataUriImageDecoder
 */
class DataUriImageDecoderTest extends TestCase
{
    protected DataUriImageDecoder $decoder;

    public function setUp(): void
    {
        $this->decoder = new DataUriImageDecoder();
    }

    public function testDecode(): void
    {
        $result = $this->decoder->decode(
            sprintf('data:image/jpeg;base64,%s', base64_encode($this->getTestImageData('blue.gif')))
        );

        $this->assertInstanceOf(Image::class, $result);
    }

    public function testDecoderNonString(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode(new stdClass());
    }

    public function testDecoderInvalid(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode('invalid');
    }

    public function testDecoderNonImage(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode('data:text/plain;charset=utf-8,test');
    }
}
