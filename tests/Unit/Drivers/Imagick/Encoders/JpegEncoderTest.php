<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Traits\CanDetectProgressiveJpeg;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\JpegEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\JpegEncoder::class)]
final class JpegEncoderTest extends ImagickTestCase
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
