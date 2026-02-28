<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Channels\Alpha;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Colors\Cmyk\Colorspace;
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
        $color = new Color(0, 0, 0, 0);
        $this->assertInstanceOf(Color::class, $color);
    }

    public function testCreate(): void
    {
        $color = Color::create('cmyk(10, 20, 30, 40)');
        $this->assertInstanceOf(Color::class, $color);

        $color = Color::create(10, 20, 30, 40);
        $this->assertInstanceOf(Color::class, $color);
    }

    public function testColorspace(): void
    {
        $color = new Color(0, 0, 0, 0);
        $this->assertInstanceOf(Colorspace::class, $color->colorspace());
    }

    public function testChannels(): void
    {
        $color = new Color(10, 20, 30, 40);
        $this->assertIsArray($color->channels());
        $this->assertCount(5, $color->channels());
    }

    public function testChannel(): void
    {
        $color = new Color(10, 20, 30, 40);
        $channel = $color->channel(Cyan::class);
        $this->assertInstanceOf(Cyan::class, $channel);
        $this->assertEquals(10, $channel->value());
    }

    public function testChannelNotFound(): void
    {
        $color = new Color(10, 20, 30, 30);
        $this->expectException(NotSupportedException::class);
        $color->channel('none');
    }

    public function testCyanMagentaYellowKey(): void
    {
        $color = new Color(10, 20, 30, 40);
        $this->assertInstanceOf(Cyan::class, $color->cyan());
        $this->assertInstanceOf(Magenta::class, $color->magenta());
        $this->assertInstanceOf(Yellow::class, $color->yellow());
        $this->assertInstanceOf(Key::class, $color->key());
        $this->assertEquals(10, $color->cyan()->value());
        $this->assertEquals(20, $color->magenta()->value());
        $this->assertEquals(30, $color->yellow()->value());
        $this->assertEquals(40, $color->key()->value());
    }

    public function testToHex(): void
    {
        $color = new Color(0, 73, 100, 0);
        $this->assertEquals('ff4400', $color->toHex());
        $this->assertEquals('#ff4400', $color->toHex('#'));
    }

    public function testIsGrayscale(): void
    {
        $color = new Color(0, 73, 100, 0);
        $this->assertFalse($color->isGrayscale());

        $color = new Color(0, 0, 0, 50);
        $this->assertTrue($color->isGrayscale());
    }

    public function testNormalize(): void
    {
        $color = new Color(100, 50, 20, 0);
        $this->assertEquals(
            [1.0, 0.5, 0.2, 0.0, 1],
            array_map(
                fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
                $color->channels(),
            )
        );
    }

    public function testToString(): void
    {
        $color = new Color(100, 50, 20, 0);
        $this->assertEquals('cmyk(100 50 20 0)', (string) $color);
    }

    public function testIsTransparent(): void
    {
        $color = new Color(100, 50, 50, 0);
        $this->assertFalse($color->isTransparent());
    }

    public function testIsClear(): void
    {
        $color = new Color(0, 0, 0, 0);
        $this->assertFalse($color->isClear());
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
        $info = (new Color(10, 20, 30, 40))->__debugInfo();
        $this->assertEquals(10, $info['cyan']);
        $this->assertEquals(20, $info['magenta']);
        $this->assertEquals(30, $info['yellow']);
        $this->assertEquals(40, $info['key']);
    }

    public function testCreateFailsInvalidArgumentCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::create(10, 20, 30);
    }

    public function testCreateFailsInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::create('not-a-color');
    }

    public function testCreateWithFiveArgs(): void
    {
        $color = Color::create(10, 20, 30, 40, .5);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(10, $color->cyan()->value());
        $this->assertEquals(20, $color->magenta()->value());
        $this->assertEquals(30, $color->yellow()->value());
        $this->assertEquals(40, $color->key()->value());
        $this->assertEquals(128, $color->alpha()->value());
    }

    public function testToStringWithAlpha(): void
    {
        $color = new Color(100, 50, 20, 0, .5);
        $this->assertEquals('cmyk(100 50 20 0 / 0.5)', (string) $color);
    }

    public function testIsTransparentTrue(): void
    {
        $color = new Color(100, 50, 50, 0, .5);
        $this->assertTrue($color->isTransparent());
    }

    public function testIsClearTrue(): void
    {
        $color = new Color(0, 0, 0, 0, 0);
        $this->assertTrue($color->isClear());
    }

    public function testConstructorWithChannelObjects(): void
    {
        $color = new Color(new Cyan(10), new Magenta(20), new Yellow(30), new Key(40), new Alpha(.5));
        $this->assertEquals(10, $color->cyan()->value());
        $this->assertEquals(20, $color->magenta()->value());
        $this->assertEquals(30, $color->yellow()->value());
        $this->assertEquals(40, $color->key()->value());
        $this->assertEquals(128, $color->alpha()->value());
    }

    public function testCloneDeepCopiesChannels(): void
    {
        $original = new Color(10, 20, 30, 40);
        $cloned = clone $original;

        $this->assertEquals(10, $original->cyan()->value());
        $this->assertEquals(10, $cloned->cyan()->value());

        // Verify they are separate objects (deep clone)
        $this->assertNotSame($original->cyan(), $cloned->cyan());
        $this->assertNotSame($original->magenta(), $cloned->magenta());
        $this->assertNotSame($original->yellow(), $cloned->yellow());
        $this->assertNotSame($original->key(), $cloned->key());
    }

    public function testToColorspace(): void
    {
        $color = new Color(0, 73, 100, 0);
        $result = $color->toColorspace(RgbColorspace::class);
        $this->assertInstanceOf(RgbColor::class, $result);

        /** @var RgbColor $result */
        $this->assertEquals(255, $result->red()->value());
        $this->assertEquals(68, $result->green()->value());
        $this->assertEquals(0, $result->blue()->value());
    }

    public function testToColorspaceWithObject(): void
    {
        $color = new Color(0, 73, 100, 0);
        $result = $color->toColorspace(new RgbColorspace());
        $this->assertInstanceOf(RgbColor::class, $result);
    }

    public function testToColorspaceFailsInvalidClass(): void
    {
        $color = new Color(0, 0, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->toColorspace('NonExistentClass');
    }

    public function testToColorspaceFailsNonColorspaceClass(): void
    {
        $color = new Color(0, 0, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->toColorspace(\stdClass::class);
    }
}
