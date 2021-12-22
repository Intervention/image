<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Drivers\Imagick\Decoders\ArrayColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Drivers\Imagick\Decoders\ArrayColorDecoder
 */
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
