<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Drivers\Imagick\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use SplFileInfo;

#[Requires('extension imagick')]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Decoders\SplFileInfoImageDecoder::class)]
class SplFileInfoImageDecoderTest extends TestCase
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
