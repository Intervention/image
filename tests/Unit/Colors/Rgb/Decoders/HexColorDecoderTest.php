<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(HexColorDecoder::class)]
final class HexColorDecoderTest extends BaseTestCase
{
    /**
     * @param $channelValues array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'rgbHex')]
    public function testDecode(mixed $input, array $channelValues): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode($input[0]);
        $this->assertEquals(
            $channelValues,
            array_map(fn(ColorChannelInterface $channel): int => $channel->value(), $result->channels()),
        );
    }

    public function testSupportsString(): void
    {
        $decoder = new HexColorDecoder();
        $this->assertTrue($decoder->supports('#fff'));
        $this->assertTrue($decoder->supports('#ffffff'));
        $this->assertTrue($decoder->supports('#ffff'));
        $this->assertTrue($decoder->supports('#ffffffff'));
        $this->assertTrue($decoder->supports('fff'));
        $this->assertTrue($decoder->supports('ffffff'));
        $this->assertTrue($decoder->supports('FF0000'));
        $this->assertTrue($decoder->supports('#FF0000'));
    }

    public function testSupportsNonString(): void
    {
        $decoder = new HexColorDecoder();
        $this->assertFalse($decoder->supports(123));
        $this->assertFalse($decoder->supports(null));
        $this->assertFalse($decoder->supports([]));
    }

    public function testSupportsInvalidStrings(): void
    {
        $decoder = new HexColorDecoder();
        $this->assertFalse($decoder->supports('xyz'));
        $this->assertFalse($decoder->supports('not-a-color'));
    }

    public function testDecodeThreeCharHex(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#f00');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 0, 0, 255], $channels);
    }

    public function testDecodeFourCharHex(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#f008');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 0, 0, 136], $channels);
    }

    public function testDecodeSixCharHex(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#ff0000');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 0, 0, 255], $channels);
    }

    public function testDecodeEightCharHex(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#ff000080');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 0, 0, 128], $channels);
    }

    public function testDecodeWithoutHash(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('ff0000');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 0, 0, 255], $channels);
    }

    public function testDecodeInvalid(): void
    {
        $decoder = new HexColorDecoder();
        $this->expectException(InvalidArgumentException::class);
        $decoder->decode('xyz-not-hex');
    }
}
