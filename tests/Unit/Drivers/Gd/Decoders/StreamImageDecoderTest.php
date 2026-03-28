<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Gd\Decoders\StreamImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Resource;

#[RequiresPhpExtension('gd')]
#[CoversClass(StreamImageDecoder::class)]
final class StreamImageDecoderTest extends GdTestCase
{
    public function testDecode(): void
    {
        $decoder = new StreamImageDecoder();
        $decoder->setDriver(new Driver());

        $result = $decoder->decode(Resource::create('test.jpg')->stream());
        $this->assertInstanceOf(Image::class, $result);
    }
}
