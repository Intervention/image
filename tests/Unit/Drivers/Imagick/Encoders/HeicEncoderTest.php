<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\HeicEncoder;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\HeicEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\HeicEncoder::class)]
final class HeicEncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new HeicEncoder(75);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/heic', $result);
        $this->assertEquals('image/heic', $result->mimetype());
    }
}
