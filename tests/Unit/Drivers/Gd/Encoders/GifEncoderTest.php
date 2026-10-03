<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use Generator;
use Intervention\Gif\Decoder;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\GifEncoder;
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

    public function testEncodeAppliesConfiguredBackgroundToSemiTransparentPixels(): void
    {
        $manager = ImageManager::usingDriver(
            Driver::class,
            backgroundColor: '0000ff',
        );

        $image = $manager->createImage(1, 1)->fill(new Color(255, 0, 0, .25));
        $result = $manager->decodeBinary((string) (new GifEncoder())->encode($image));

        $this->assertColor(64, 0, 191, 255, $result->colorAt(0, 0), 32);
    }

    public function testEncodeKeepsFullTransparency(): void
    {
        $manager = ImageManager::usingDriver(
            Driver::class,
            backgroundColor: '0000ff',
        );

        $image = $manager->createImage(1, 1)->fill(new Color(255, 0, 0, 0));
        $result = $manager->decodeBinary((string) (new GifEncoder())->encode($image));

        $this->assertTrue($result->colorAt(0, 0)->isClear());
    }

    public function testEncodeKeepsOpaquePixelsWhenBackgroundIsVeryClose(): void
    {
        $manager = ImageManager::usingDriver(
            Driver::class,
            backgroundColor: 'ff0000',
        );

        $image = $manager->createImage(4, 4)->fill(new Color(248, 0, 0, 1));
        $result = $manager->decodeBinary((string) (new GifEncoder())->encode($image));

        $this->assertFalse($result->colorAt(1, 1)->isClear());
        $this->assertColor(248, 0, 0, 255, $result->colorAt(1, 1), 8);
    }

    public function testEncodeOpaqueImageWithoutBackgroundLikeColorsStaysOpaque(): void
    {
        $manager = ImageManager::usingDriver(
            Driver::class,
            backgroundColor: 'ff0000',
        );

        $image = $manager->createImage(8, 8)->fill(new Color(0, 0, 255, 1));
        $result = $manager->decodeBinary((string) (new GifEncoder())->encode($image));

        $this->assertFalse($result->colorAt(4, 4)->isClear());
        $this->assertColor(0, 0, 255, 255, $result->colorAt(4, 4), 4);
    }

    #[DataProvider('encodeBackgroundTransparencyDataProvider')]
    public function testEncodeBackgroundTransparency(Color $background, Color $color, Color $blended): void
    {
        $manager = ImageManager::usingDriver(Driver::class, backgroundColor: $background);
        $image = $manager->createImage(10, 10)->fill($color)->resizeCanvasRelative(20, 20, new Color(0, 0, 0, 0));
        $result = $manager->decodeBinary((string) (new GifEncoder())->encode($image));
        $blendedPixel = $result->colorAt(16, 16);

        $this->assertTrue($result->colorAt(0, 0)->isClear());

        if ($blended->isClear()) {
            $this->assertTrue($blendedPixel->isClear());
        } else {
            $this->assertColor(
                $blended->red()->value(),
                $blended->green()->value(),
                $blended->blue()->value(),
                $blended->alpha()->value(),
                $blendedPixel,
                4,
            );
        }
    }

    public static function encodeBackgroundTransparencyDataProvider(): Generator
    {
        yield [
            new Color(255, 0, 255, 1),
            new Color(0, 255, 0, 1),
            new Color(0, 255, 0, 1),
        ];

        yield [
            new Color(255, 0, 0, 1),
            new Color(0, 255, 0, .75),
            new Color(64, 191, 0, 1),
        ];

        yield [
            new Color(255, 0, 0, 1),
            new Color(0, 255, 0, .5),
            new Color(127, 127, 0, 1),
        ];

        yield [
            new Color(255, 0, 0, 1),
            new Color(0, 255, 0, .25),
            new Color(191, 64, 0, 1),
        ];

        yield [
            new Color(255, 0, 0, 1),
            new Color(0, 255, 0, 0),
            new Color(0, 255, 0, 0),
        ];

        yield [
            new Color(255, 0, 255, .75),
            new Color(0, 255, 0, 1),
            new Color(0, 255, 0, 1),
        ];

        yield [
            new Color(255, 0, 255, .75),
            new Color(0, 255, 0, .75),
            new Color(51, 204, 51, 1),
        ];

        yield [
            new Color(255, 0, 255, .75),
            new Color(0, 255, 0, .5),
            new Color(109, 146, 109, 1),
        ];

        yield [
            new Color(255, 0, 255, .75),
            new Color(0, 255, 0, .25),
            new Color(172, 78, 172, 1),
        ];

        yield [
            new Color(255, 0, 255, .75),
            new Color(0, 255, 0, 0),
            new Color(0, 255, 0, 0),
        ];
    }
}
