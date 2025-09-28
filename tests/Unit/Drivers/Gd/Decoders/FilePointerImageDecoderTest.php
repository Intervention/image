<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Gd\Decoders\FilePointerImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Tests\Resource;

#[RequiresPhpExtension('gd')]
#[CoversClass(FilePointerImageDecoder::class)]
final class FilePointerImageDecoderTest extends GdTestCase
{
    public function testDecode(): void
    {
        $decoder = new FilePointerImageDecoder();
        $decoder->setDriver(new Driver());

        $result = $decoder->decode(Resource::create('test.jpg')->pointer());
        $this->assertInstanceOf(Image::class, $result);
    }
}
