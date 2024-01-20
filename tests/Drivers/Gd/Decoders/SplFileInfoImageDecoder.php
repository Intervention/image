<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use SplFileInfo;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Decoders\SplFileInfoImageDecoder
 */
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
