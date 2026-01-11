<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklch\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Oklch\Decoders\StringColorDecoder;
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
}
