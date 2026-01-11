<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Decoders\HtmlColornameDecoder;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(HtmlColorNameDecoder::class)]
final class HtmlColornameDecoderTest extends BaseTestCase
{
    /**
     * @param $channelValues array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'rgbColorname')]
    public function testDecode(mixed $input, array $channelValues): void
    {
        $decoder = new HtmlColornameDecoder();
        $result = $decoder->decode($input[0]);
        $this->assertEquals(
            $channelValues,
            array_map(
                fn(ColorChannelInterface $channel): int|float => $channel->value(),
                $result->channels()
            )
        );
    }
}
