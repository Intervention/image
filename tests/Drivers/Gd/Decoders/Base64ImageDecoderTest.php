<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Drivers\Gd\Decoders\Base64ImageDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Drivers\Gd\Decoders\Base64ImageDecoder::class)]
final class Base64ImageDecoderTest extends TestCase
{
    protected Base64ImageDecoder $decoder;

    protected function setUp(): void
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
