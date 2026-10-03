<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use Generator;
use Intervention\Gif\Decoder;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
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

        $sourceCorner = $image->colorAt(0, 0);
        $result = ImageManager::usingDriver(Driver::class)->decodeBinary(
            (string) (new GifEncoder())->encode($image),
        );
        $decodedCorner = $result->colorAt(0, 0);

        var_dump([
            'php' => PHP_VERSION,
            'gd' => gd_info(),
            'expected_clear' => $isClear,
            'background' => [
                'r' => $background->channel(Red::class)->value(),
                'g' => $background->channel(Green::class)->value(),
                'b' => $background->channel(Blue::class)->value(),
                'a' => $background->channel(Alpha::class)->value(),
            ],
            'source_corner' => [
                'r' => $sourceCorner->channel(Red::class)->value(),
                'g' => $sourceCorner->channel(Green::class)->value(),
                'b' => $sourceCorner->channel(Blue::class)->value(),
                'a' => $sourceCorner->channel(Alpha::class)->value(),
                'isClear' => $sourceCorner->isClear(),
            ],
            'decoded_corner' => [
                'r' => $decodedCorner->channel(Red::class)->value(),
                'g' => $decodedCorner->channel(Green::class)->value(),
                'b' => $decodedCorner->channel(Blue::class)->value(),
                'a' => $decodedCorner->channel(Alpha::class)->value(),
                'isClear' => $decodedCorner->isClear(),
            ],
            'decoded_transparent_index' => imagecolortransparent($result->core()->native()),
        ]);

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
