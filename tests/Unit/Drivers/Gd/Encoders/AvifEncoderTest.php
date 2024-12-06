<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Gd\Encoders\AvifEncoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(AvifEncoder::class)]
final class AvifEncoderTest extends GdTestCase
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
