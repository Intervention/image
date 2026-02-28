<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Decoders\TransparentColorDecoder;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TransparentColorDecoder::class)]
final class TransparentColorDecoderTest extends BaseTestCase
{
    public function testSupportsString(): void
    {
        $decoder = new TransparentColorDecoder();
        $this->assertTrue($decoder->supports('transparent'));
        $this->assertTrue($decoder->supports('Transparent'));
        $this->assertTrue($decoder->supports('TRANSPARENT'));
    }

    public function testSupportsNonString(): void
    {
        $decoder = new TransparentColorDecoder();
        $this->assertFalse($decoder->supports(123));
        $this->assertFalse($decoder->supports(null));
        $this->assertFalse($decoder->supports([]));
    }

    public function testSupportsInvalidString(): void
    {
        $decoder = new TransparentColorDecoder();
        $this->assertFalse($decoder->supports('red'));
        $this->assertFalse($decoder->supports('#fff'));
        $this->assertFalse($decoder->supports('not-transparent'));
    }

    public function testDecode(): void
    {
        $decoder = new TransparentColorDecoder();
        $result = $decoder->decode('transparent');
        $channels = array_map(
            fn(ColorChannelInterface $c): int => $c->value(),
            $result->channels()
        );
        // transparent = #ffffff00 = r:255, g:255, b:255, a:0
        $this->assertEquals([255, 255, 255, 0], $channels);
    }
}
