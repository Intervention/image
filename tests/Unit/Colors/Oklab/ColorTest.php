<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklab;

use Intervention\Image\Colors\Oklab\Channels\A;
use Intervention\Image\Colors\Oklab\Channels\B;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Oklab\Colorspace as OklabColorspace;
use Intervention\Image\Colors\Oklab\Channels\Lightness;
use Intervention\Image\Colors\Oklab\Color;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace;
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
        $color = Color::create('oklab(0%, 25%, -50%)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([0.0, .1, -0.2], $color->toArray());

        $color = Color::create(.51, .1, -.2);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([.51, .1, -.2], $color->toArray());
    }

    public function testColorspace(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(OklabColorspace::class, $color->colorspace());
    }

    public function testChannels(): void
    {
        $color = new Color(0, .1, .2);
        $this->assertIsArray($color->channels());
        $this->assertCount(3, $color->channels());
    }

    public function testChannel(): void
    {
        $color = new Color(0, .1, .2);

        $channel = $color->channel(Lightness::class);
        $this->assertInstanceOf(Lightness::class, $channel);
        $this->assertEquals(0, $channel->value());

        $channel = $color->channel(A::class);
        $this->assertInstanceOf(A::class, $channel);
        $this->assertEquals(.1, $channel->value());

        $channel = $color->channel(B::class);
        $this->assertInstanceOf(B::class, $channel);
        $this->assertEquals(.2, $channel->value());
    }

    public function testChannelNotFound(): void
    {
        $color = new Color(0, .1, .2);
        $this->expectException(NotSupportedException::class);
        $color->channel('none');
    }

    public function testLightnessAB(): void
    {
        $color = new Color(0, .1, .2);
        $this->assertInstanceOf(Lightness::class, $color->lightness());
        $this->assertInstanceOf(A::class, $color->a());
        $this->assertInstanceOf(B::class, $color->b());
        $this->assertEquals(0, $color->lightness()->value());
        $this->assertEquals(.1, $color->a()->value());
        $this->assertEquals(.2, $color->b()->value());
    }

    public function testToArray(): void
    {
        $color = new Color(0, .1, .2);
        $this->assertEquals([0, .1, .2], $color->toArray());
    }

    public function testToHex(): void
    {
        $color = new Color(0.64905124115, 0.19974263609074, 0.13044605841927);
        $this->assertEquals('ff3700', $color->toHex());
    }

    public function testNormalize(): void
    {
        $color = new Color(1, .2, -0.4);
        $this->assertEquals([1.0, 0.7500000000000001, 0.0], $color->normalize());

        $color = new Color(0, 0, 0);
        $this->assertEquals([0, 0.5, 0.5], $color->normalize());
    }

    public function testToString(): void
    {
        $color = new Color(0, .1, -0.2);
        $this->assertEquals('oklab(0 0.1 -0.2)', (string) $color);
    }

    public function testToColorspace(): void
    {
        $color = new Color(0.64905124115, 0.19974263609074, 0.13044605841927);
        $converted = $color->toColorspace(Colorspace::class);
        $this->assertInstanceOf(RgbColor::class, $converted);
        $this->assertEquals([255, 55, 0, 1], $converted->toArray());
    }

    public function testIsGreyscale(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertTrue($color->isGreyscale());

        $color = new Color(.5, .10, -0.2);
        $this->assertFalse($color->isGreyscale());
    }

    public function testDebugInfo(): void
    {
        $info = (new Color(0, .1, .2))->__debugInfo();
        $this->assertEquals(0, $info['lightness']);
        $this->assertEquals(.1, $info['a']);
        $this->assertEquals(.2, $info['b']);
    }
}
