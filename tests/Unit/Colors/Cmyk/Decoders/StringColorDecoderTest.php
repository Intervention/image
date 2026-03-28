<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Cmyk\Decoders;

use Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder;
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
    #[DataProviderExternal(ColorDataProvider::class, 'cmykString')]
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
        $this->assertTrue($decoder->supports('cmyk(0, 0, 0, 0)'));
        $this->assertTrue($decoder->supports('CMYK(100, 50, 25, 10)'));
        $this->assertFalse($decoder->supports('rgb(0, 0, 0)'));
        $this->assertFalse($decoder->supports(123));
        $this->assertFalse($decoder->supports(null));
    }

    public function testDecodeInvalid(): void
    {
        $decoder = new StringColorDecoder();
        $this->expectException(InvalidArgumentException::class);
        $decoder->decode('cmyk(invalid)');
    }
}
