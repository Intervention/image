<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Imagick\Decoders\StreamImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Resource;

#[RequiresPhpExtension('imagick')]
#[CoversClass(StreamImageDecoder::class)]
final class StreamImageDecoderTest extends ImagickTestCase
{
    public function testDecode(): void
    {
        $decoder = new StreamImageDecoder();
        $decoder->setDriver(new Driver());
        $result = $decoder->decode(Resource::create('test.jpg')->stream());
        $this->assertInstanceOf(Image::class, $result);
    }
}
