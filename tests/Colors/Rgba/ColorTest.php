<?php

namespace Intervention\Image\Tests\Colors\Rgba;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgba\Color;
use Intervention\Image\Colors\Rgba\Channels\Red;
use Intervention\Image\Colors\Rgba\Channels\Green;
use Intervention\Image\Colors\Rgba\Channels\Blue;
use Intervention\Image\Colors\Rgba\Channels\Alpha;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Colors\Rgba\Color
 */
class ColorTest extends TestCase
{
    public function testConstructor(): void
    {
        $color = new Color(0, 0, 0, 0);
        $this->assertInstanceOf(Color::class, $color);
    }

    public function testChannels(): void
    {
        $color = new Color(10, 20, 30, 255);
        $this->assertIsArray($color->channels());
        $this->assertCount(4, $color->channels());
    }

    public function testChannel(): void
    {
        $color = new Color(10, 20, 30, 255);
        $channel = $color->channel(Red::class);
        $this->assertInstanceOf(Red::class, $channel);
        $this->assertEquals(10, $channel->value());
        $channel = $color->channel(Alpha::class);
        $this->assertInstanceOf(Alpha::class, $channel);
        $this->assertEquals(255, $channel->value());
    }

    public function testRedGreenBlueAlpha(): void
    {
        $color = new Color(10, 20, 30, 255);
        $this->assertInstanceOf(Red::class, $color->red());
        $this->assertInstanceOf(Green::class, $color->green());
        $this->assertInstanceOf(Blue::class, $color->blue());
        $this->assertInstanceOf(Alpha::class, $color->alpha());
        $this->assertEquals(10, $color->red()->value());
        $this->assertEquals(20, $color->green()->value());
        $this->assertEquals(30, $color->blue()->value());
        $this->assertEquals(255, $color->alpha()->value());
    }

    public function testToArray(): void
    {
        $color = new Color(10, 20, 30, 255);
        $this->assertEquals([10, 20, 30, 255], $color->toArray());
    }

    public function testToHex(): void
    {
        $color = new Color(181, 55, 23, 0);
        $this->assertEquals('b5371700', $color->toHex());
        $this->assertEquals('#b5371700', $color->toHex('#'));

        $color = new Color(181, 55, 23, 255);
        $this->assertEquals('b53717', $color->toHex());

        $color = new Color(181, 55, 23, 204);
        $this->assertEquals('b53717cc', $color->toHex());
    }

    public function testNormalize(): void
    {
        $color = new Color(255, 0, 51, 255);
        $this->assertEquals([1.0, 0.0, 0.2, 1.0], $color->normalize());
        $color = new Color(255, 0, 51, 51);
        $this->assertEquals([1.0, 0.0, 0.2, 0.2], $color->normalize());
    }

    public function testToString(): void
    {
        $color = new Color(255, 255, 255, 255);
        $this->assertEquals('rgba(255, 255, 255, 1.0)', (string) $color);

        $color = new Color(10, 20, 30, 85);
        $this->assertEquals('rgba(10, 20, 30, 0.3)', (string) $color);
    }

    public function testToRgba(): void
    {
        $color = new Color(181, 55, 23, 120);
        $converted = $color->toRgba();
        $this->assertInstanceOf(Color::class, $converted);
    }

    public function testToRgb(): void
    {
        $color = new Color(181, 55, 23, 120);
        $converted = $color->toRgb();
        $this->assertInstanceOf(RgbColor::class, $converted);
        $this->assertEquals([181, 55, 23], $converted->toArray());
    }

    public function testToCmyk(): void
    {
        $color = new Color(0, 0, 0, 255);
        $converted = $color->toCmyk();
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals([0, 0, 0, 100], $converted->toArray());

        $color = new Color(0, 0, 0, 0);
        $converted = $color->toCmyk();
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals([0, 0, 0, 100], $converted->toArray());
    }
}
