<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Traits\CanInspectPng;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Encoders\PngEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Encoders\PngEncoder::class)]
final class PngEncoderTest extends GdTestCase
{
    use CanInspectPng;

    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new PngEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/png', (string) $result);
        $this->assertFalse($this->isInterlacedPng((string) $result));
    }

    public function testEncodeInterlaced(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new PngEncoder(interlaced: true);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/png', (string) $result);
        $this->assertTrue($this->isInterlacedPng((string) $result));
    }

    public function testEncoderInitialFormat(): void
    {
        $image = $this->createTestImage(3, 2);
        $result = (new PngEncoder())->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));

        $image = $this->createTestImageTransparent(3, 2);
        $result = (new PngEncoder())->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));

        $image = $this->createTestImageTransparent(3, 2)->fill('fff');
        $result = (new PngEncoder())->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));

        $image = $this->readTestImage('tile.png'); // indexed with alpha
        $result = (new PngEncoder())->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));

        $image = $this->readTestImage('indexed.png'); // indexed
        $result = (new PngEncoder())->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));
    }

    public function testEncoderTransformFormat(): void
    {
        $image = $this->createTestImage(3, 2); // truecolor-alpha
        $result = (new PngEncoder(indexed: true))->encode($image);
        $this->assertEquals('indexed', $this->pngColorType((string) $result));

        $image = $this->createTestImageTransparent(3, 2); // truecolor-alpha
        $result = (new PngEncoder(indexed: true))->encode($image);
        $this->assertEquals('indexed', $this->pngColorType((string) $result));

        $image = $this->createTestImageTransparent(3, 2)->fill('fff'); // truecolor-alpha
        $result = (new PngEncoder(indexed: true))->encode($image);
        $this->assertEquals('indexed', $this->pngColorType((string) $result));

        $image = $this->readTestImage('tile.png'); // indexed with alpha
        $result = (new PngEncoder(indexed: false))->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));

        $image = $this->readTestImage('indexed.png'); // indexed
        $result = (new PngEncoder(indexed: false))->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));
    }
}
