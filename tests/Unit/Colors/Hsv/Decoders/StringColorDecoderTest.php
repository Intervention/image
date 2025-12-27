<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsv\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
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
        $this->assertEquals($channelValues, $result->toArray());
    }
}
