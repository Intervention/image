<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\JpegEncoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Traits\CanDetectProgressiveJpeg;

#[RequiresPhpExtension('gd')]
#[CoversClass(JpegEncoder::class)]
final class JpegEncoderTest extends GdTestCase
{
    use CanDetectProgressiveJpeg;

    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new JpegEncoder(75);
        $encoder->setDriver(new Driver());
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jpeg', $result);
        $this->assertEquals('image/jpeg', $result->mimetype());
    }

    public function testEncodeProgressive(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new JpegEncoder(progressive: true);
        $encoder->setDriver(new Driver());
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jpeg', $result);
        $this->assertEquals('image/jpeg', $result->mimetype());
        $this->assertTrue($this->isProgressiveJpeg($result));
    }
}
