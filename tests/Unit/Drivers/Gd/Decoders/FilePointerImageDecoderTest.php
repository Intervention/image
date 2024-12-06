<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Gd\Decoders\FilePointerImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(FilePointerImageDecoder::class)]
final class FilePointerImageDecoderTest extends GdTestCase
{
    public function testDecode(): void
    {
        $decoder = new FilePointerImageDecoder();
        $decoder->setDriver(new Driver());

        $fp = fopen($this->getTestResourcePath('test.jpg'), 'r');
        $result = $decoder->decode($fp);
        $this->assertInstanceOf(Image::class, $result);
    }
}
