<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgba\Color as RgbaColor;
use Intervention\Image\Drivers\Imagick\Decoders\RgbStringColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Decoders\RgbStringColorDecoder
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
        $color = $decoder->decode('rgba(181, 55, 23, 0.2)');
        $this->assertInstanceOf(RgbaColor::class, $color);
        $this->assertEquals([181, 55, 23, 51], $color->toArray());
    }
}
