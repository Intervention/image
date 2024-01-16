<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder
 */
class StringColorDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode('rgb(204, 204, 204)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('rgb(204,204,204)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('rgb(100%,20%,0%)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([255, 51, 0, 255], $result->toArray());

        $result = $decoder->decode('rgb(100%,19.8064%,0.1239483%)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([255, 51, 0, 255], $result->toArray());

        $result = $decoder->decode('rgba(204, 204, 204, 1)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 255], $result->toArray());

        $result = $decoder->decode('rgba(204,204,204,.2)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 51], $result->toArray());

        $result = $decoder->decode('rgba(204,204,204,0.2)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([204, 204, 204, 51], $result->toArray());

        $result = $decoder->decode('srgb(255, 0, 0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([255, 0, 0, 255], $result->toArray());
    }
}
