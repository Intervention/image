<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Decoders;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Decoders\ColorObjectDecoder;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ColorObjectDecoder::class)]
final class ColorObjectDecoderTest extends BaseTestCase
{
    public function testSupportsColorObject(): void
    {
        $decoder = new ColorObjectDecoder();
        $color = new RgbColor(
            new Red(255),
            new Green(0),
            new Blue(0),
            new Alpha(1)
        );
        $this->assertTrue($decoder->supports($color));
    }

    public function testSupportsNonColorObject(): void
    {
        $decoder = new ColorObjectDecoder();
        $this->assertFalse($decoder->supports('not a color'));
        $this->assertFalse($decoder->supports(123));
        $this->assertFalse($decoder->supports(null));
        $this->assertFalse($decoder->supports(new \stdClass()));
    }

    public function testDecode(): void
    {
        $decoder = new ColorObjectDecoder();
        $color = new RgbColor(
            new Red(255),
            new Green(0),
            new Blue(0),
            new Alpha(1)
        );
        $result = $decoder->decode($color);
        $this->assertInstanceOf(ColorInterface::class, $result);
        $this->assertSame($color, $result);
    }

    public function testDecodeInvalid(): void
    {
        $decoder = new ColorObjectDecoder();
        $this->expectException(InvalidArgumentException::class);
        $decoder->decode('not a color object');
    }
}
