<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Hsl\Decoders;

use Intervention\Image\Colors\Hsl\Color;
use Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder
 */
class StringColorDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new StringColorDecoder();

        $result = $decoder->decode('hsl(0,0,0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 0, 0], $result->toArray());

        $result = $decoder->decode('hsl(0, 100, 50)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 50], $result->toArray());

        $result = $decoder->decode('hsl(360, 100, 50)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([360, 100, 50], $result->toArray());

        $result = $decoder->decode('hsl(180, 100%, 50%)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([180, 100, 50], $result->toArray());
    }
}
