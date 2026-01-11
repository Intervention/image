<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
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
}
