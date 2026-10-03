<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use Generator;
use Intervention\Gif\Decoder;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\GifEncoder;
use Intervention\Image\Drivers\Gd\Modifiers\CropModifier;
use Intervention\Image\ImageManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(GifEncoder::class)]
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
            Decoder::decode((string) $result)->firstFrame()->imageDescriptor()->isInterlaced(),
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
            Decoder::decode((string) $result)->firstFrame()->imageDescriptor()->isInterlaced(),
        );
    }

    public function testEncodeInterlacedAnimation(): void
    {
        $image = $this->createTestAnimation();
        $encoder = new GifEncoder(interlaced: true);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/gif', $result);
        $this->assertEquals('image/gif', $result->mimetype());
        $this->assertTrue(
            Decoder::decode((string) $result)->firstFrame()->imageDescriptor()->isInterlaced(),
        );
    }

    #[DataProvider('encodeBackgroundTransparencyDataProvider')]
    public function testEncodeBackgroundTransparency(Color $background, bool $isClear): void
    {
        $image = $this->createTestImage(10, 10);
        $image->modify(new CropModifier(20, 20, -5, -5, $background));
        $result = ImageManager::usingDriver(Driver::class)->decodeBinary(
            (string) (new GifEncoder())->encode($image),
        );

        $this->assertEquals($isClear, $result->colorAt(0, 0)->isClear());
        $this->assertColor(255, 0, 0, 255, $result->colorAt(10, 10), 4);
    }

    public static function encodeBackgroundTransparencyDataProvider(): Generator
    {
        yield [new Color(255, 255, 0, 0), true];
        yield [new Color(255, 255, 0, .25), true];
        yield [new Color(255, 255, 0, .75), false];
        yield [new Color(255, 255, 0, 1), false];
    }
}
