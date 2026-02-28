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
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\InvalidArgumentException;
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
        $this->assertEquals('hsv(100 50% 20% / 0.2)', (string) $color);
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

    public function testCreateFailsInvalidArgumentCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::create(10, 20);
    }

    public function testCreateFailsInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::create('not-a-color');
    }

    public function testCreateWithFourArgs(): void
    {
        $color = Color::create(180, 50, 50, .5);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(180, $color->hue()->value());
        $this->assertEquals(50, $color->saturation()->value());
        $this->assertEquals(50, $color->value()->value());
        $this->assertEquals(128, $color->alpha()->value());
    }

    public function testConstructorWithChannelObjects(): void
    {
        $color = new Color(new Hue(180), new Saturation(50), new Value(50), new Alpha(.5));
        $this->assertEquals(180, $color->hue()->value());
        $this->assertEquals(50, $color->saturation()->value());
        $this->assertEquals(50, $color->value()->value());
        $this->assertEquals(128, $color->alpha()->value());
    }

    public function testCloneDeepCopiesChannels(): void
    {
        $original = new Color(180, 50, 50);
        $cloned = clone $original;

        $this->assertEquals(180, $original->hue()->value());
        $this->assertEquals(180, $cloned->hue()->value());

        // Verify they are separate objects (deep clone)
        $this->assertNotSame($original->hue(), $cloned->hue());
        $this->assertNotSame($original->saturation(), $cloned->saturation());
        $this->assertNotSame($original->value(), $cloned->value());
    }

    public function testToColorspace(): void
    {
        $color = new Color(16, 100, 100);
        $result = $color->toColorspace(RgbColorspace::class);
        $this->assertInstanceOf(RgbColor::class, $result);
    }

    public function testToColorspaceWithObject(): void
    {
        $color = new Color(16, 100, 100);
        $result = $color->toColorspace(new RgbColorspace());
        $this->assertInstanceOf(RgbColor::class, $result);
    }

    public function testToColorspaceFailsInvalidClass(): void
    {
        $color = new Color(0, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->toColorspace('NonExistentClass');
    }

    public function testToColorspaceFailsNonColorspaceClass(): void
    {
        $color = new Color(0, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->toColorspace(\stdClass::class);
    }
}
