<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Color;
use Intervention\Image\Drivers\Gd\Decoders\TransparentColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 */
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
