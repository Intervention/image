<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Gd\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;
use SplFileInfo;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Drivers\Gd\Decoders\SplFileInfoImageDecoder::class)]
final class SplFileInfoImageDecoderTest extends BaseTestCase
{
    public function testDecode(): void
    {
        $decoder = new SplFileInfoImageDecoder();
        $result = $decoder->decode(
            new SplFileInfo($this->getTestImagePath('blue.gif'))
        );
        $this->assertInstanceOf(Image::class, $result);
    }
}
