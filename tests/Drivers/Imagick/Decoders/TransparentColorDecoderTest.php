<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Drivers\Imagick\Decoders\TransparentColorDecoder;
use Intervention\Image\Tests\TestCase;

class TransparentColorDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new TransparentColorDecoder();
        $color = $decoder->decode('transparent');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(0, $color->red());
        $this->assertEquals(0, $color->green());
        $this->assertEquals(0, $color->blue());
        $this->assertEquals(0, $color->alpha());
    }
}
