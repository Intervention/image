<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Color;
use Intervention\Image\Drivers\Gd\Decoders\RgbStringColorDecoder;
use Intervention\Image\Tests\TestCase;

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
