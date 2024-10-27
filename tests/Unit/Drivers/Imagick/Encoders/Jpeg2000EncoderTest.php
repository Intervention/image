<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\Jpeg2000Encoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\Jpeg2000Encoder::class)]
final class Jpeg2000EncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new Jpeg2000Encoder(75);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jp2', $result);
        $this->assertEquals('image/jp2', $result->mimetype());
    }
}
