<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\TiffEncoder;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\TiffEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\TiffEncoder::class)]
final class TiffEncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new TiffEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/tiff', $result);
        $this->assertEquals('image/tiff', $result->mimetype());
    }
}
