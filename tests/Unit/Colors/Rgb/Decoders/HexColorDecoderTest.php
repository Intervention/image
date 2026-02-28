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
        $this->assertFalse($decoder->supports(1.5));
        $this->assertFalse($decoder->supports(true));
        $this->assertFalse($decoder->supports(false));
        $this->assertFalse($decoder->supports(new \stdClass()));
    }

    public function testSupportsEmptyString(): void
    {
        $decoder = new HexColorDecoder();
        $this->assertFalse($decoder->supports(''));
    }

    public function testSupportsInvalidStrings(): void
    {
        $decoder = new HexColorDecoder();
        $this->assertFalse($decoder->supports('xyz'));
        $this->assertFalse($decoder->supports('not-a-color'));
        $this->assertFalse($decoder->supports('gggggg'));
        $this->assertFalse($decoder->supports('aabbccdde'));
        $this->assertFalse($decoder->supports('aabbccddee'));
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

    public function testDecodeThreeCharWithoutHash(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('f00');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 0, 0, 255], $channels);
    }

    public function testDecodeFourCharWithoutHash(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('f008');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 0, 0, 136], $channels);
    }

    public function testDecodeEightCharWithoutHash(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('ff000080');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 0, 0, 128], $channels);
    }

    public function testDecodeCaseInsensitive(): void
    {
        $decoder = new HexColorDecoder();
        $lower = $decoder->decode('#ff0000');
        $upper = $decoder->decode('#FF0000');
        $lowerChannels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $lower->channels());
        $upperChannels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $upper->channels());
        $this->assertEquals($lowerChannels, $upperChannels);
    }

    public function testDecodeBlack(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#000000');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([0, 0, 0, 255], $channels);
    }

    public function testDecodeWhite(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#ffffff');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 255, 255, 255], $channels);
    }

    public function testDecodeFullTransparent(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#00000000');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([0, 0, 0, 0], $channels);
    }

    public function testDecodeShorthandBlack(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#000');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([0, 0, 0, 255], $channels);
    }

    public function testDecodeShorthandWhite(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#fff');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([255, 255, 255, 255], $channels);
    }

    public function testDecodeShorthandWithAlpha(): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode('#0000');
        $channels = array_map(fn(ColorChannelInterface $c): int => $c->value(), $result->channels());
        $this->assertEquals([0, 0, 0, 0], $channels);
    }

    public function testDecodeInvalid(): void
    {
        $decoder = new HexColorDecoder();
        $this->expectException(InvalidArgumentException::class);
        $decoder->decode('xyz-not-hex');
    }

    public function testDecodeInvalidShortString(): void
    {
        $decoder = new HexColorDecoder();
        $this->expectException(InvalidArgumentException::class);
        $decoder->decode('zz');
    }
}
