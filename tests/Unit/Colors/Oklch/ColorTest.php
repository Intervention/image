<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklch;

use Intervention\Image\Colors\Oklch\Channels\Alpha;
use Intervention\Image\Colors\Oklch\Channels\Hue;
use Intervention\Image\Colors\Oklch\Channels\Chroma;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Oklch\Colorspace as OklchColorspace;
use Intervention\Image\Colors\Oklch\Channels\Lightness;
use Intervention\Image\Colors\Oklch\Color;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Color::class)]
final class ColorTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(Color::class, $color);
    }

    public function testCreate(): void
    {
        $color = Color::create('oklch(0%, 0.123, 180)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(
            [0.0, .123, 180, 255],
            array_map(
                fn(ColorChannelInterface $channel): int|float => $channel->value(),
                $color->channels()
            )
        );

        $color = Color::create(.51, -0.1, 2);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(
            [.51, -0.1, 2, 255],
            array_map(
                fn(ColorChannelInterface $channel): int|float => $channel->value(),
                $color->channels()
            )
        );

        $color = Color::create(.51, -0.1, 2, .2);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(
            [.51, -0.1, 2, 51],
            array_map(
                fn(ColorChannelInterface $channel): int|float => $channel->value(),
                $color->channels()
            )
        );
    }

    public function testColorspace(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(OklchColorspace::class, $color->colorspace());
    }

    public function testChannels(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertIsArray($color->channels());
        $this->assertCount(4, $color->channels());
    }

    public function testChannel(): void
    {
        $color = new Color(0, .1, 2);

        $channel = $color->channel(Lightness::class);
        $this->assertInstanceOf(Lightness::class, $channel);
        $this->assertEquals(0, $channel->value());

        $channel = $color->channel(Chroma::class);
        $this->assertInstanceOf(Chroma::class, $channel);
        $this->assertEquals(.1, $channel->value());

        $channel = $color->channel(Hue::class);
        $this->assertInstanceOf(Hue::class, $channel);
        $this->assertEquals(2, $channel->value());

        $channel = $color->channel(Alpha::class);
        $this->assertInstanceOf(Alpha::class, $channel);
        $this->assertEquals(255, $channel->value());
    }

    public function testChannelNotFound(): void
    {
        $color = new Color(0, .1, 2);
        $this->expectException(NotSupportedException::class);
        $color->channel('none');
    }

    public function testLightnessChromaHue(): void
    {
        $color = new Color(0, .1, 2);
        $this->assertInstanceOf(Lightness::class, $color->lightness());
        $this->assertInstanceOf(Hue::class, $color->hue());
        $this->assertInstanceOf(Chroma::class, $color->chroma());
        $this->assertEquals(0, $color->lightness()->value());
        $this->assertEquals(.1, $color->chroma()->value());
        $this->assertEquals(2, $color->hue()->value());
    }

    public function testToHex(): void
    {
        $color = new Color(0.6759, 0.21747, 38.8022);
        $this->assertEquals('ff5500', $color->toHex());

        $color = new Color(0.6759, 0.21747, 38.8022, .2);
        $this->assertEquals('ff550033', $color->toHex());
    }

    public function testNormalize(): void
    {
        $color = new Color(1, -0.4, 180);
        $this->assertEquals([1.0, 0, .5, 1], $color->normalizedChannelValues());

        $color = new Color(1, 0.4, 90);
        $this->assertEquals([1.0, 1.0, .25, 1], $color->normalizedChannelValues());
    }

    public function testToString(): void
    {
        $color = new Color(0, .1, 2);
        $this->assertEquals('oklch(0 0.1 2)', (string) $color);
    }

    public function testToColorspace(): void
    {
        $color = new Color(0.6759, 0.21747, 38.8022);
        $converted = $color->toColorspace(Rgb::class);
        $this->assertInstanceOf(RgbColor::class, $converted);
        $this->assertEquals(
            [255, 85, 0, 255],
            array_map(
                fn(ColorChannelInterface $channel): int|float => $channel->value(),
                $converted->channels()
            )
        );
    }

    public function testIsGrayscale(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertTrue($color->isGrayscale());

        $color = new Color(0, .1, 2);
        $this->assertFalse($color->isGrayscale());
    }

    public function testDebugInfo(): void
    {
        $info = (new Color(0, .1, 2))->__debugInfo();
        $this->assertEquals('0', $info['lightness']);
        $this->assertEquals('0.1', $info['chroma']);
        $this->assertEquals('2', $info['hue']);
        $this->assertEquals('1', $info['alpha']);
    }
}
