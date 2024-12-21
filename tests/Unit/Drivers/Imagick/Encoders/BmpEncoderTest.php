<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use Intervention\Image\Drivers\Imagick\Encoders\BmpEncoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(BmpEncoder::class)]
final class BmpEncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new BmpEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType(['image/bmp', 'image/x-ms-bmp'], $result);
        $this->assertEquals('image/bmp', $result->mimetype());
    }
}
