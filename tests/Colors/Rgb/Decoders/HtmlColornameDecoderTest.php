<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Rgb\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Decoders\HtmlColornameDecoder;
use Intervention\Image\Tests\TestCase;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Colors\Rgb\Decoders\HtmlColorNameDecoder::class)]
final class HtmlColornameDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new HtmlColornameDecoder();
        $result = $decoder->decode('salmon');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([250, 128, 114, 255], $result->toArray());

        $result = $decoder->decode('khaki');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([240, 230, 140, 255], $result->toArray());

        $result = $decoder->decode('peachpuff');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([255, 218, 185, 255], $result->toArray());
    }
}
