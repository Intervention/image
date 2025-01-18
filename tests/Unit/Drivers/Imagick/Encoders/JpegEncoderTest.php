<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use Intervention\Image\Drivers\Imagick\Decoders\FilePointerImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Encoders\JpegEncoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Traits\CanDetectProgressiveJpeg;

#[RequiresPhpExtension('imagick')]
#[CoversClass(JpegEncoder::class)]
final class JpegEncoderTest extends ImagickTestCase
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

    public function testEncodeStripExif(): void
    {
        $image = $this->readTestImage('exif.jpg');
        $this->assertEquals('Oliver Vogel', $image->exif('IFD0.Artist'));

        $encoder = new JpegEncoder(strip: true);
        $encoder->setDriver(new Driver());
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jpeg', $result);
        $this->assertEquals('image/jpeg', $result->mimetype());

        $this->assertEmpty(exif_read_data($result->toFilePointer())['IFD0.Artist'] ?? null);
    }

    public function testEncodeStripExifKeepICCProfiles(): void
    {
        $image = $this->readTestImage('cmyk.jpg');
        $this->assertNotEmpty($image->core()->native()->getImageProfiles('icc'));

        $encoder = new JpegEncoder(strip: true);
        $encoder->setDriver(new Driver());
        $result = $encoder->encode($image);

        $decoder = new FilePointerImageDecoder();
        $decoder->setDriver(new Driver());

        $image = $decoder->decode($result->toFilePointer());
        $this->assertNotEmpty($image->core()->native()->getImageProfiles('icc'));
    }
}
