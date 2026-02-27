<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(StringColorDecoder::class)]
final class StringColorDecoderTest extends BaseTestCase
{
    /**
     * @param $channelValues array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'rgbString')]
    public function testDecode(mixed $input, array $channelValues): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode($input[0]);
        $this->assertEquals(
            $channelValues,
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $result->channels(),
            ),
        );
    }

    /**
     * @param $channelValues array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'rgbStringInvalid')]
    public function testDecodeInvalid(string $input): void
    {
        $decoder = new StringColorDecoder();
        $this->expectException(InvalidArgumentException::class);
        $decoder->decode($input);
    }

    public function testSupportsString(): void
    {
        $decoder = new StringColorDecoder();
        $this->assertTrue($decoder->supports('rgb(255, 0, 0)'));
        $this->assertTrue($decoder->supports('rgba(255, 0, 0, 1)'));
        $this->assertTrue($decoder->supports('srgb(255, 0, 0)'));
        $this->assertTrue($decoder->supports('srgba(255, 0, 0, 1)'));
        $this->assertTrue($decoder->supports('RGB(255, 0, 0)'));
    }

    public function testSupportsNonString(): void
    {
        $decoder = new StringColorDecoder();
        $this->assertFalse($decoder->supports(123));
        $this->assertFalse($decoder->supports(null));
        $this->assertFalse($decoder->supports([]));
    }

    public function testSupportsInvalidString(): void
    {
        $decoder = new StringColorDecoder();
        $this->assertFalse($decoder->supports('not-a-color'));
        $this->assertFalse($decoder->supports('#fff'));
        $this->assertFalse($decoder->supports('hsl(0, 100%, 50%)'));
    }
}
