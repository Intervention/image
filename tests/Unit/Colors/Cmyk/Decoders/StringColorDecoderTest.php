<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Cmyk\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(StringColorDecoder::class)]
final class StringColorDecoderTest extends BaseTestCase
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

    public function testDecodeInvalid(): void
    {
        $decoder = new StringColorDecoder();
        $this->expectException(DecoderException::class);
        $decoder->decode(null);
    }
}
