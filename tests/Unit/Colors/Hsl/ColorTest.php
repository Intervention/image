<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsl;

use Intervention\Image\Colors\Hsl\Channels\Alpha;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Hsl\Channels\Hue;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Colors\Hsl\Channels\Saturation;
use Intervention\Image\Colors\Hsl\Color;
use Intervention\Image\Colors\Hsl\Colorspace;
use Intervention\Image\Exceptions\NotSupportedException;
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
        $color = Color::create('hsl(10, 20, 30)');
        $this->assertInstanceOf(Color::class, $color);

        $color = Color::create(10, 20, 30);
        $this->assertInstanceOf(Color::class, $color);
    }

    public function testColorspace(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(Colorspace::class, $color->colorspace());
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
        $channel = $color->channel(Hue::class);
        $this->assertInstanceOf(Hue::class, $channel);
        $this->assertEquals(10, $channel->value());
    }

    public function testChannelNotFound(): void
    {
        $color = new Color(10, 20, 30);
        $this->expectException(NotSupportedException::class);
        $color->channel('none');
    }

    public function testHueSaturationLuminanceKey(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertInstanceOf(Hue::class, $color->hue());
        $this->assertInstanceOf(Saturation::class, $color->saturation());
        $this->assertInstanceOf(Luminance::class, $color->luminance());
        $this->assertInstanceOf(Alpha::class, $color->alpha());
        $this->assertEquals(10, $color->hue()->value());
        $this->assertEquals(20, $color->saturation()->value());
        $this->assertEquals(30, $color->luminance()->value());
        $this->assertEquals(1, $color->alpha()->value());
    }

    public function testToArray(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertEquals([10, 20, 30, 1], $color->toArray());
    }

    public function testToHex(): void
    {
        $color = new Color(16, 100, 50);
        $this->assertEquals('ff4400', $color->toHex());
    }

    public function testNormalize(): void
    {
        $color = new Color(180, 50, 25);
        $this->assertEquals([.5, 0.5, 0.25, 1], $color->normalizedChannelValues());
    }

    public function testToString(): void
    {
        $color = new Color(100, 50, 20, 0);
        $this->assertEquals('hsl(100 50% 20%)', (string) $color);
    }

    public function testIsGrayscale(): void
    {
        $color = new Color(0, 1, 0);
        $this->assertFalse($color->isGrayscale());

        $color = new Color(1, 0, 0);
        $this->assertTrue($color->isGrayscale());

        $color = new Color(0, 0, 1);
        $this->assertTrue($color->isGrayscale());
    }

    public function testIsTransparent(): void
    {
        $color = new Color(0, 1, 0);
        $this->assertFalse($color->isTransparent());
    }

    public function testIsClear(): void
    {
        $color = new Color(0, 1, 0);
        $this->assertFalse($color->isClear());
    }

    public function testDebugInfo(): void
    {
        $info = (new Color(10, 20, 30))->__debugInfo();
        $this->assertEquals(10, $info['hue']);
        $this->assertEquals(20, $info['saturation']);
        $this->assertEquals(30, $info['luminance']);
    }
}
