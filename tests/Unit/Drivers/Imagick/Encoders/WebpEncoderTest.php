<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\WebpEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\WebpEncoder::class)]
final class WebpEncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new WebpEncoder(75);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/webp', $result);
        $this->assertEquals('image/webp', $result->mimetype());
    }
}
