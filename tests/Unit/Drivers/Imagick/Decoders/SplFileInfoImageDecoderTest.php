<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Imagick\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;
use SplFileInfo;

#[RequiresPhpExtension('imagick')]
#[CoversClass(SplFileInfoImageDecoder::class)]
final class SplFileInfoImageDecoderTest extends BaseTestCase
{
    public function testDecode(): void
    {
        $decoder = new SplFileInfoImageDecoder();
        $decoder->setDriver(new Driver());
        $result = $decoder->decode(
            new SplFileInfo($this->getTestResourcePath('blue.gif'))
        );
        $this->assertInstanceOf(Image::class, $result);
    }
}
