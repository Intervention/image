<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder
 */
class HexColorDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('ccc');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('ccff33');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 255, 51, 255], $result->toArray());

        $result = $decoder->decode('#ccc');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('cccccc');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('#cccccc');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('#ccccccff');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('#cccf');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('ccccccff');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('cccf');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('#b53717aa');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([181, 55, 23, 170], $result->toArray());
    }
}
