<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Cmyk\Decoders;

use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder
 */
class StringColorDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode('cmyk(0,0,0,0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 0, 0, 0], $result->toArray());

        $result = $decoder->decode('cmyk(0, 100, 100, 0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 100, 0], $result->toArray());

        $result = $decoder->decode('cmyk(0, 100, 100, 0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 100, 0], $result->toArray());


        $result = $decoder->decode('cmyk(0%, 100%, 100%, 0%)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 100, 0], $result->toArray());
    }
}
