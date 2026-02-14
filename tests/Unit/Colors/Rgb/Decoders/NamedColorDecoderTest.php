<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Decoders\NamedColorDecoder;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(NamedColorDecoder::class)]
final class NamedColorDecoderTest extends BaseTestCase
{
    /**
     * @param $channelValues array<int>
     */
    #[DataProviderExternal(ColorDataProvider::class, 'rgbNamedColor')]
    public function testDecode(mixed $input, array $channelValues): void
    {
        $decoder = new NamedColorDecoder();
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
