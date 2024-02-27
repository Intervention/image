<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Drivers\Gd\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use SplFileInfo;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Drivers\Gd\Decoders\SplFileInfoImageDecoder::class)]
final class SplFileInfoImageDecoderTest extends TestCase
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
