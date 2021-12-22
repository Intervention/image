<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Drivers\Imagick\Decoders\RgbStringColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 */
class RgbStringColorDecoderTest extends TestCase
{
    public function testDecodeRgb(): void
    {
        $decoder = new RgbStringColorDecoder();
        $color = $decoder->decode('rgb(181, 55, 23)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(181, $color->red());
        $this->assertEquals(55, $color->green());
        $this->assertEquals(23, $color->blue());
        $this->assertEquals(1, $color->alpha());
    }

    public function testDecodeRgba(): void
    {
        $decoder = new RgbStringColorDecoder();
        $color = $decoder->decode('rgba(181, 55, 23, 0.5)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(181, $color->red());
        $this->assertEquals(55, $color->green());
        $this->assertEquals(23, $color->blue());
        $this->assertEquals(.5, $color->alpha());
    }
}
