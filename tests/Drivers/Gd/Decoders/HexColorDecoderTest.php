<?php

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Color;
use Intervention\Image\Drivers\Gd\Decoders\HexColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Drivers\Gd\Decoders\HexColorDecoder
 */
class HexColorDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('ccc');
        $this->assertInstanceOf(Color::class, $result);

        $result = $decoder->decode('#ccc');
        $this->assertInstanceOf(Color::class, $result);

        $result = $decoder->decode('cccccc');
        $this->assertInstanceOf(Color::class, $result);

        $result = $decoder->decode('#cccccc');
        $this->assertInstanceOf(Color::class, $result);
    }
}
