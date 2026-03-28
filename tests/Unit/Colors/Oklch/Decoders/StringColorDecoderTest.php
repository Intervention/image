<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklch\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Oklch\Decoders\StringColorDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(StringColorDecoder::class)]
final class StringColorDecoderTest extends BaseTestCase
{
    /**
     * @param $channelValues array<float>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'oklchString')]
    public function testDecode(mixed $input, array $channelValues): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode($input[0]);
        $this->assertEquals(
            $channelValues,
            array_map(
                fn(ColorChannelInterface $channel): int|float => $channel->value(),
                $result->channels(),
            ),
        );
    }

    public function testSupportsOklchString(): void
    {
        $decoder = new StringColorDecoder();
        $this->assertTrue($decoder->supports('oklch(0.5 0.1 120)'));
        $this->assertTrue($decoder->supports('OKLCH(0.5 0.1 120)'));
        $this->assertTrue($decoder->supports('oklch(0.5, 0.1, 120)'));
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
        $this->assertFalse($decoder->supports('rgb(255, 0, 0)'));
        $this->assertFalse($decoder->supports('oklab(0.5 0.1 -0.1)'));
        $this->assertFalse($decoder->supports('not a color'));
    }

    public function testDecodeInvalid(): void
    {
        $decoder = new StringColorDecoder();
        $this->expectException(InvalidArgumentException::class);
        $decoder->decode('oklch(invalid)');
    }

    public function testDecodeWithPercentageValues(): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode('oklch(50% 10% 120)');
        $channels = $result->channels();
        // 50% of lightness max (1.0) = 0.5
        $this->assertEqualsWithDelta(0.5, $channels[0]->value(), 0.01);
    }

    public function testDecodeWithAlpha(): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode('oklch(0.5 0.1 120 / 0.5)');
        $channels = $result->channels();
        $this->assertCount(4, $channels);
        // Alpha value() returns internal int (0-255 range), 0.5 * 255 = 128
        $this->assertEqualsWithDelta(128, $channels[3]->value(), 1);
    }

    public function testDecodeWithAlphaPercentage(): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode('oklch(0.5 0.1 120 / 50%)');
        $channels = $result->channels();
        $this->assertCount(4, $channels);
        // Alpha value() returns internal int (0-255 range), 50% = 0.5 * 255 = 128
        $this->assertEqualsWithDelta(128, $channels[3]->value(), 1);
    }
}
