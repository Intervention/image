<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Traits\CanInspectPngFormat;

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
}
