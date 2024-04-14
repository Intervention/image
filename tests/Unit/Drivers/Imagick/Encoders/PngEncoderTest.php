<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Traits\CanDetectInterlacedPng;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\PngEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\PngEncoder::class)]
final class PngEncoderTest extends ImagickTestCase
{
    use CanDetectInterlacedPng;

    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new PngEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/png', (string) $result);
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
