<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsv\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Hsv\Color;
use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder;
use Intervention\Image\Tests\BaseTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder::class)]
final class StringColorDecoderTest extends BaseTestCase
{
    public function testDecodeHsv(): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode('hsv(0,0,0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 0, 0], $result->toArray());

        $result = $decoder->decode('hsv(0, 100, 100)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 100], $result->toArray());

        $result = $decoder->decode('hsv(360, 100, 100)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([360, 100, 100], $result->toArray());


        $result = $decoder->decode('hsv(180, 100%, 100%)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([180, 100, 100], $result->toArray());
    }

    public function testDecodeHsb(): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode('hsb(0,0,0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 0, 0], $result->toArray());

        $result = $decoder->decode('hsb(0, 100, 100)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 100], $result->toArray());

        $result = $decoder->decode('hsb(360, 100, 100)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([360, 100, 100], $result->toArray());


        $result = $decoder->decode('hsb(180, 100%, 100%)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([180, 100, 100], $result->toArray());
    }
}
