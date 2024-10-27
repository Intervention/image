<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Encoders\WebpEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Encoders\WebpEncoder::class)]
final class WebpEncoderTest extends GdTestCase
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
