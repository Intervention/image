<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Traits\CanDetectProgressiveJpeg;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Encoders\JpegEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Encoders\JpegEncoder::class)]
final class JpegEncoderTest extends GdTestCase
{
    use CanDetectProgressiveJpeg;

    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new JpegEncoder(75);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jpeg', $result);
        $this->assertEquals('image/jpeg', $result->mimetype());
    }

    public function testEncodeProgressive(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new JpegEncoder(progressive: true);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jpeg', $result);
        $this->assertEquals('image/jpeg', $result->mimetype());
        $this->assertTrue($this->isProgressiveJpeg($result));
    }
}
