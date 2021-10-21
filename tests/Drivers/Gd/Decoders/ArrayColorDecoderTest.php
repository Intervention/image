<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Color;
use Intervention\Image\Drivers\Gd\Decoders\ArrayColorDecoder;
use Intervention\Image\Tests\TestCase;

class ArrayColorDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new ArrayColorDecoder();
        $color = $decoder->decode([181, 55, 23, .5]);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(181, $color->red());
        $this->assertEquals(55, $color->green());
        $this->assertEquals(23, $color->blue());
        $this->assertEquals(.5, $color->alpha());
    }
}
