<?php

namespace Intervention\Image\Tests\Colors\Rgb;

use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Color as Color;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgb\Color
 */
class ColorTest extends TestCase
{
    public function testConstructor(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(Color::class, $color);
    }

    public function testChannels(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertIsArray($color->channels());
        $this->assertCount(3, $color->channels());
    }

    public function testChannel(): void
    {
        $color = new Color(10, 20, 30);
        $channel = $color->channel(Red::class);
        $this->assertInstanceOf(Red::class, $channel);
        $this->assertEquals(10, $channel->value());
    }

    public function testRedGreenBlue(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertInstanceOf(Red::class, $color->red());
        $this->assertInstanceOf(Green::class, $color->green());
        $this->assertInstanceOf(Blue::class, $color->blue());
        $this->assertEquals(10, $color->red()->value());
        $this->assertEquals(20, $color->green()->value());
        $this->assertEquals(30, $color->blue()->value());
    }

    public function testToArray(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertEquals([10, 20, 30], $color->toArray());
    }

    public function testToHex(): void
    {
        $color = new Color(181, 55, 23);
        $this->assertEquals('b53717', $color->toHex());
        $this->assertEquals('#b53717', $color->toHex('#'));
    }

    public function testNormalize(): void
    {
        $color = new Color(255, 0, 51);
        $this->assertEquals([1.0, 0.0, 0.2], $color->normalize());
    }

    public function testToInt(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertEquals(0, $color->toInt());

        $color = new Color(255, 255, 255);
        $this->assertEquals(16777215, $color->toInt());

        $color = new Color(181, 55, 23);
        $this->assertEquals(11876119, $color->toInt());
    }

    public function testToString(): void
    {
        $color = new Color(181, 55, 23);
        $this->assertEquals('rgb(181, 55, 23)', (string) $color);
    }

    public function testToCmyk(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertEquals([0, 0, 0, 100], $color->toCmyk()->toArray());

        $color = new Color(255, 255, 255);
        $this->assertEquals([0, 0, 0, 0], $color->toCmyk()->toArray());

        $color = new Color(255, 0, 0);
        $this->assertEquals([0, 100, 100, 0], $color->toCmyk()->toArray());

        $color = new Color(255, 0, 255);
        $this->assertEquals([0, 100, 0, 0], $color->toCmyk()->toArray());

        $color = new Color(255, 255, 0);
        $this->assertEquals([0, 0, 100, 0], $color->toCmyk()->toArray());

        $color = new Color(255, 204, 204);
        $this->assertEquals([0, 20, 20, 0], $color->toCmyk()->toArray());
    }
}
