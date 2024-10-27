<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\AvifEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\AvifEncoder::class)]
final class AvifEncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new AvifEncoder(10);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/avif', $result);
        $this->assertEquals('image/avif', $result->mimetype());
    }
}
