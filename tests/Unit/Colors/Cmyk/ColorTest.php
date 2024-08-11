<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Cmyk;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Colors\Cmyk\Colorspace;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\BaseTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Colors\Cmyk\Color::class)]
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
        $this->assertCount(4, $color->channels());
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
        $this->expectException(ColorException::class);
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

    public function testToArray(): void
    {
        $color = new Color(10, 20, 30, 40);
        $this->assertEquals([10, 20, 30, 40], $color->toArray());
    }

    public function testToHex(): void
    {
        $color = new Color(0, 73, 100, 0);
        $this->assertEquals('ff4400', $color->toHex());
        $this->assertEquals('#ff4400', $color->toHex('#'));
    }

    public function testIsGreyscale(): void
    {
        $color = new Color(0, 73, 100, 0);
        $this->assertFalse($color->isGreyscale());

        $color = new Color(0, 0, 0, 50);
        $this->assertTrue($color->isGreyscale());
    }

    public function testNormalize(): void
    {
        $color = new Color(100, 50, 20, 0);
        $this->assertEquals([1.0, 0.5, 0.2, 0.0], $color->normalize());
    }

    public function testToString(): void
    {
        $color = new Color(100, 50, 20, 0);
        $this->assertEquals('cmyk(100%, 50%, 20%, 0%)', (string) $color);
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
}
