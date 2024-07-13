<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Traits\CanInspectPng;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\PngEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\PngEncoder::class)]
final class PngEncoderTest extends ImagickTestCase
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

        $image = $this->readTestImage('tile.png');
        $result = (new PngEncoder())->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));

        $image = $this->readTestImage('indexed.png');
        $result = (new PngEncoder())->encode($image);
        $this->assertEquals('truecolor-alpha', $this->pngColorType((string) $result));
    }
}
