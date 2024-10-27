<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use Intervention\Gif\Decoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Encoders\GifEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Encoders\GifEncoder::class)]
final class GifEncoderTest extends GdTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestAnimation();
        $encoder = new GifEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/gif', $result);
        $this->assertEquals('image/gif', $result->mimetype());
        $this->assertFalse(
            Decoder::decode((string) $result)->getFirstFrame()->getImageDescriptor()->isInterlaced()
        );
    }

    public function testEncodeInterlaced(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new GifEncoder(interlaced: true);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/gif', $result);
        $this->assertEquals('image/gif', $result->mimetype());
        $this->assertTrue(
            Decoder::decode((string) $result)->getFirstFrame()->getImageDescriptor()->isInterlaced()
        );
    }

    public function testEncodeInterlacedAnimation(): void
    {
        $image = $this->createTestAnimation(3, 2);
        $encoder = new GifEncoder(interlaced: true);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/gif', $result);
        $this->assertEquals('image/gif', $result->mimetype());
        $this->assertTrue(
            Decoder::decode((string) $result)->getFirstFrame()->getImageDescriptor()->isInterlaced()
        );
    }
}
