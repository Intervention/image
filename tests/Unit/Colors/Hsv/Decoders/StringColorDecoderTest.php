<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsv\Decoders;

use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder;
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
    #[DataProviderExternal(ColorDataProvider::class, 'hsvString')]
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

    public function testSupportsString(): void
    {
        $decoder = new StringColorDecoder();
        $this->assertTrue($decoder->supports('hsv(0, 0%, 0%)'));
        $this->assertTrue($decoder->supports('HSV(360, 100%, 100%)'));
        $this->assertTrue($decoder->supports('hsb(0, 0%, 0%)'));
        $this->assertTrue($decoder->supports('HSB(360, 100%, 100%)'));
        $this->assertFalse($decoder->supports('rgb(0, 0, 0)'));
        $this->assertFalse($decoder->supports(123));
        $this->assertFalse($decoder->supports(null));
    }

    public function testDecodeInvalid(): void
    {
        $decoder = new StringColorDecoder();
        $this->expectException(InvalidArgumentException::class);
        $decoder->decode('hsv(invalid)');
    }
}
