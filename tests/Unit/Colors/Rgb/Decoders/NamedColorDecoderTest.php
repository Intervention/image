<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\Decoders\NamedColorDecoder;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
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

    public function testSupportsString(): void
    {
        $decoder = new NamedColorDecoder();
        $this->assertTrue($decoder->supports('red'));
        $this->assertTrue($decoder->supports('blue'));
        $this->assertTrue($decoder->supports('green'));
        $this->assertTrue($decoder->supports('white'));
        $this->assertTrue($decoder->supports('black'));
        $this->assertTrue($decoder->supports('RED'));
        $this->assertTrue($decoder->supports('Red'));
    }

    public function testSupportsNonString(): void
    {
        $decoder = new NamedColorDecoder();
        $this->assertFalse($decoder->supports(123));
        $this->assertFalse($decoder->supports(null));
        $this->assertFalse($decoder->supports([]));
    }

    public function testSupportsInvalidString(): void
    {
        $decoder = new NamedColorDecoder();
        $this->assertFalse($decoder->supports('not-a-color-name'));
        $this->assertFalse($decoder->supports('#fff'));
        $this->assertFalse($decoder->supports('rgb(0,0,0)'));
    }
}
