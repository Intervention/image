<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsv;

use Intervention\Image\Colors\Hsv\Channels\Alpha;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Hsv\Channels\Hue;
use Intervention\Image\Colors\Hsv\Channels\Saturation;
use Intervention\Image\Colors\Hsv\Channels\Value;
use Intervention\Image\Colors\Hsv\Color;
use Intervention\Image\Colors\Hsv\Colorspace;
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
        $color = Color::create('hsv(10, 20, 30)');
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

    public function testHueSaturationValueKey(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertInstanceOf(Hue::class, $color->hue());
        $this->assertInstanceOf(Saturation::class, $color->saturation());
        $this->assertInstanceOf(Value::class, $color->value());
        $this->assertEquals(10, $color->hue()->value());
        $this->assertEquals(20, $color->saturation()->value());
        $this->assertEquals(30, $color->value()->value());
    }

    public function testToHex(): void
    {
        $color = new Color(16, 100, 100);
        $this->assertEquals('ff4400', $color->toHex());
    }

    public function testNormalize(): void
    {
        $color = new Color(180, 50, 25);
        $this->assertEquals(
            [.5, 0.5, 0.25, 1],
            array_map(
                fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
                $color->channels(),
            )
        );

        $color = new Color(180, 50, 25, .2);
        $this->assertEquals(
            [.5, 0.5, 0.25, .2],
            array_map(
                fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
                $color->channels(),
            )
        );
    }

    public function testToString(): void
    {
        $color = new Color(100, 50, 20);
        $this->assertEquals('hsv(100 50% 20%)', (string) $color);

        $color = new Color(100, 50, 20, 1);
        $this->assertEquals('hsv(100 50% 20%)', (string) $color);

        $color = new Color(100, 50, 20, .2);
        $this->assertEquals('hsv(100 50 20 / 0.2)', (string) $color);
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
        $color = new Color(1, 0, 0);
        $this->assertFalse($color->isTransparent());

        $color = new Color(1, 0, 0, 1);
        $this->assertFalse($color->isTransparent());

        $color = new Color(1, 0, 0, .2);
        $this->assertTrue($color->isTransparent());

        $color = new Color(1, 0, 0, 0);
        $this->assertTrue($color->isTransparent());
    }

    public function testIsClear(): void
    {
        $color = new Color(0, 1, 0);
        $this->assertFalse($color->isClear());

        $color = new Color(0, 1, 0, 1);
        $this->assertFalse($color->isClear());

        $color = new Color(0, 1, 0, .2);
        $this->assertFalse($color->isClear());

        $color = new Color(0, 1, 0, 0);
        $this->assertTrue($color->isClear());
    }

    public function testSetTransparency(): void
    {
        $color = new Color(0, 0, 0, 1);
        $result = $color->withTransparency(.2);
        $this->assertEquals(255, $color->channel(Alpha::class)->value());
        $this->assertEquals(51, $result->channel(Alpha::class)->value());
    }

    public function testDebugInfo(): void
    {
        $info = (new Color(10, 20, 30))->__debugInfo();
        $this->assertEquals('10', $info['hue']);
        $this->assertEquals('20', $info['saturation']);
        $this->assertEquals('30', $info['value']);
        $this->assertEquals('1', $info['alpha']);
    }
}
