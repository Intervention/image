<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\GifEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\GifEncoder::class)]
final class GifEncoderTest extends ImagickTestCase
{
    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new GifEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/gif', (string) $result);
    }
}
