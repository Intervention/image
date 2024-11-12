<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Traits\CanInspectPngFormat;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Encoders\PngEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Encoders\PngEncoder::class)]
final class PngEncoderTest extends GdTestCase
{
    use CanInspectPngFormat;

    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new PngEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/png', $result);
        $this->assertEquals('image/png', $result->mimetype());
        $this->assertFalse($this->isInterlacedPng($result));
    }

    public function testEncodeInterlaced(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new PngEncoder(interlaced: true);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/png', $result);
        $this->assertEquals('image/png', $result->mimetype());
        $this->assertTrue($this->isInterlacedPng($result));
    }

    #[DataProvider('indexedDataProvider')]
    public function testEncoderIndexed(ImageInterface $image, PngEncoder $encoder, string $result): void
    {
        $this->assertEquals(
            $result,
            $this->pngColorType($encoder->encode($image)),
        );
    }

    public static function indexedDataProvider(): Generator
    {
        yield [
            static::createTestImage(3, 2), // new
            new PngEncoder(indexed: false),
            'truecolor-alpha',
        ];
        yield [
            static::createTestImage(3, 2), // new
            new PngEncoder(indexed: true),
            'indexed',
        ];
        yield [
            static::readTestImage('circle.png'), // truecolor-alpha
            new PngEncoder(indexed: false),
            'truecolor-alpha',
        ];
        yield [
            static::readTestImage('circle.png'), // indexedcolor-alpha
            new PngEncoder(indexed: true),
            'indexed',
        ];
        yield [
            static::readTestImage('tile.png'), // indexed
            new PngEncoder(indexed: false),
            'truecolor-alpha',
        ];
        yield [
            static::readTestImage('tile.png'), // indexed
            new PngEncoder(indexed: true),
            'indexed',
        ];
        yield [
            static::readTestImage('test.jpg'), // jpeg
            new PngEncoder(indexed: false),
            'truecolor-alpha',
        ];
        yield [
            static::readTestImage('test.jpg'), // jpeg
            new PngEncoder(indexed: true),
            'indexed',
        ];
    }
}
