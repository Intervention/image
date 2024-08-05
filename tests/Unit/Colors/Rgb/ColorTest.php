<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\BaseTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Colors\Rgb\Color::class)]
final class ColorTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(Color::class, $color);

        $color = new Color(0, 0, 0, 0);
        $this->assertInstanceOf(Color::class, $color);
    }

    public function testCreate(): void
    {
        $color = Color::create('ccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 255], $color->toArray());

        $color = Color::create('rgba(10, 20, 30, .2)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([10, 20, 30, 51], $color->toArray());
    }

    public function testColorspace(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(RgbColorspace::class, $color->colorspace());
    }

    public function testChannels(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertIsArray($color->channels());
        $this->assertCount(4, $color->channels());
    }

    public function testChannel(): void
    {
        $color = new Color(10, 20, 30);
        $channel = $color->channel(Red::class);
        $this->assertInstanceOf(Red::class, $channel);
        $this->assertEquals(10, $channel->value());
    }

    public function testChannelNotFound(): void
    {
        $color = new Color(10, 20, 30);
        $this->expectException(ColorException::class);
        $color->channel('none');
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
        $this->assertEquals([10, 20, 30, 255], $color->toArray());
    }

    public function testToHex(): void
    {
        $color = new Color(181, 55, 23);
        $this->assertEquals('b53717', $color->toHex());
        $this->assertEquals('#b53717', $color->toHex('#'));

        $color = new Color(181, 55, 23, 51);
        $this->assertEquals('b5371733', $color->toHex());
    }

    public function testNormalize(): void
    {
        $color = new Color(255, 0, 51);
        $this->assertEquals([1.0, 0.0, 0.2, 1.0], $color->normalize());
    }

    public function testToString(): void
    {
        $color = new Color(181, 55, 23);
        $this->assertEquals('rgb(181, 55, 23)', (string) $color);
    }

    public function testConvertTo(): void
    {
        $color = new Color(0, 0, 0);
        $converted = $color->convertTo(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals([0, 0, 0, 100], $converted->toArray());

        $color = new Color(255, 255, 255);
        $converted = $color->convertTo(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals([0, 0, 0, 0], $converted->toArray());

        $color = new Color(255, 0, 0);
        $converted = $color->convertTo(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals([0, 100, 100, 0], $converted->toArray());

        $color = new Color(255, 0, 255);
        $converted = $color->convertTo(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals([0, 100, 0, 0], $converted->toArray());

        $color = new Color(255, 255, 0);
        $converted = $color->convertTo(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals([0, 0, 100, 0], $converted->toArray());

        $color = new Color(255, 204, 204);
        $converted = $color->convertTo(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals([0, 20, 20, 0], $converted->toArray());
    }

    public function testIsGreyscale(): void
    {
        $color = new Color(255, 0, 100);
        $this->assertFalse($color->isGreyscale());

        $color = new Color(50, 50, 50);
        $this->assertTrue($color->isGreyscale());
    }

    public function testIsTransparent(): void
    {
        $color = new Color(255, 255, 255);
        $this->assertFalse($color->isTransparent());

        $color = new Color(255, 255, 255, 255);
        $this->assertFalse($color->isTransparent());

        $color = new Color(255, 255, 255, 85);
        $this->assertTrue($color->isTransparent());

        $color = new Color(255, 255, 255, 0);
        $this->assertTrue($color->isTransparent());
    }

    public function testIsClear(): void
    {
        $color = new Color(255, 255, 255);
        $this->assertFalse($color->isClear());

        $color = new Color(255, 255, 255, 255);
        $this->assertFalse($color->isClear());

        $color = new Color(255, 255, 255, 85);
        $this->assertFalse($color->isClear());

        $color = new Color(255, 255, 255, 0);
        $this->assertTrue($color->isClear());
    }
}
