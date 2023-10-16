<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Drivers\Gd\Decoders\RgbStringColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Decoders\RgbStringColorDecoder
 */
class RgbStringColorDecoderTest extends TestCase
{
    public function testDecodeRgb(): void
    {
        $decoder = new RgbStringColorDecoder();
        $color = $decoder->decode('rgb(181, 55, 23)');
        $this->assertInstanceOf(RgbColor::class, $color);
        $this->assertEquals([181, 55, 23], $color->toArray());
    }

    public function testDecodeRgba(): void
    {
        $decoder = new RgbStringColorDecoder();
        $color = $decoder->decode('rgba(181, 55, 23, 0.5)');
        $this->assertInstanceOf(RgbColor::class, $color);
        $this->assertEquals([181, 55, 23, 51], $color->toArray());
    }
}
