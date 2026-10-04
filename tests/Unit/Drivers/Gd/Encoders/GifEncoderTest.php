<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use Intervention\Gif\Decoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\GifEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Modifiers\CropModifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;

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
    public function testEncodeBackgroundTransparency(string $background, bool $transparent): void
    {
        $image = $this->createTestImage(10, 10);
        $image->modify(new CropModifier(20, 20, -5, -5, $background));
        $result = ImageManager::usingDriver(Driver::class)->decodeBinary(
            (string) (new GifEncoder())->encode($image),
        );

        // only clear backgrounds become transparent, as gif only supports binary transparency
        $this->assertEquals($transparent, $result->colorAt(0, 0)->isClear());
        $this->assertColor(255, 0, 0, 255, $result->colorAt(10, 10), 4);
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function encodeBackgroundTransparencyDataProvider(): array
    {
        return [
            'clear' => ['ffff0000', true],
            'quarter opaque' => ['ffff0040', false],
            'three quarters opaque' => ['ffff00c0', false],
            'opaque' => ['ffff00', false],
        ];
    }
}
