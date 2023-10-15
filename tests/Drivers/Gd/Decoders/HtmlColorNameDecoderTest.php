<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Gd\Decoders\HtmlColorNameDecoder;
use Intervention\Image\Tests\TestCase;

class HtmlColorNameDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new HtmlColorNameDecoder();
        $color = $decoder->decode('tomato');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals('ff6347', $color->toHex());
    }
}
