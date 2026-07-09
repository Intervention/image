<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Encoders\JxlEncoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(JxlEncoder::class)]
final class JxlEncoderTest extends ImagickTestCase
{
    protected function setUp(): void
    {
        if (Imagick::queryFormats('JXL') === []) {
            $this->markTestSkipped('ImageMagick was built without JXL (libjxl) support');
        }
    }

    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new JxlEncoder(75);
        $encoder->setDriver(new Driver());
        $result = $encoder->encode($image);
        $this->assertMediaType(['image/jxl', 'image/x-jxl'], $result);
        $this->assertEquals('image/jxl', $result->mimetype());
    }
}
