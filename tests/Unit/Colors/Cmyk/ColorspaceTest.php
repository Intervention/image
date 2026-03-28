<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Channels\Alpha;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Cmyk\Colorspace;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Rgb\NamedColor;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

#[CoversClass(Colorspace::class)]
final class ColorspaceTest extends BaseTestCase
{
    public function testColorFromNormalized(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->colorFromNormalized([0, 1, 0, 1]);
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(100, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(100, $result->channel(Key::class)->value());
        $this->assertEquals(255, $result->channel(Alpha::class)->value());

        $result = $colorspace->colorFromNormalized([0, 1, 0, 1, .2]);
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(100, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(100, $result->channel(Key::class)->value());
        $this->assertEquals(51, $result->channel(Alpha::class)->value());
    }

    public function testImportRgbColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new RgbColor(255, 0, 255));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(100, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(0, $result->channel(Key::class)->value());

        $result = $colorspace->importColor(new RgbColor(127, 127, 127));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(0, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(50, $result->channel(Key::class)->value());

        $result = $colorspace->importColor(new RgbColor(127, 127, 127, 0.3333333333));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(0, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(50, $result->channel(Key::class)->value());
        $this->assertEquals(85, $result->channel(Alpha::class)->value());
    }

    public function testImportHsvColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new HsvColor(0, 0, 50));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(0, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(50, $result->channel(Key::class)->value());
    }

    public function testImportHslColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new HslColor(300, 100, 50));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(100, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(0, $result->channel(Key::class)->value());

        $result = $colorspace->importColor(new HslColor(0, 0, 50));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(0, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(50, $result->channel(Key::class)->value());
    }

    public function testImportNamedColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(NamedColor::BLACK);
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(0, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(100, $result->channel(Key::class)->value());
    }

    public function testImportOklabColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new OklabColor(0.68, 0.17, 0.14));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEqualsWithDelta(67, $result->channel(Magenta::class)->value(), 2);
        $this->assertEqualsWithDelta(100, $result->channel(Yellow::class)->value(), 1);
        $this->assertEquals(0, $result->channel(Key::class)->value());
    }

    public function testImportOklchColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new OklchColor(0.68, 0.22, 38.8));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEqualsWithDelta(67, $result->channel(Magenta::class)->value(), 2);
        $this->assertEqualsWithDelta(100, $result->channel(Yellow::class)->value(), 1);
        $this->assertEquals(0, $result->channel(Key::class)->value());
    }

    public function testImportCmykColorPassthrough(): void
    {
        $colorspace = new Colorspace();

        $color = new CmykColor(10, 20, 30, 40);
        $result = $colorspace->importColor($color);
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertSame($color, $result);
    }

    public function testColorFromNormalizedInvalidChannelCount(): void
    {
        $colorspace = new Colorspace();
        $this->expectException(InvalidArgumentException::class);
        $colorspace->colorFromNormalized([0.5, 0.5]);
    }

    public function testColorFromNormalizedWithNullValue(): void
    {
        $colorspace = new Colorspace();
        $this->expectException(InvalidArgumentException::class);
        $colorspace->colorFromNormalized([0.5, null, 0.5, 0.5]);
    }

    public function testImportUnsupportedColor(): void
    {
        $colorspace = new Colorspace();
        $color = Mockery::mock(\Intervention\Image\Interfaces\ColorInterface::class);
        $this->expectException(NotSupportedException::class);
        $colorspace->importColor($color);
    }

    public function testChannels(): void
    {
        $channels = Colorspace::channels();
        $this->assertIsArray($channels);
        $this->assertCount(5, $channels);
        $this->assertEquals(Cyan::class, $channels[0]);
        $this->assertEquals(Magenta::class, $channels[1]);
        $this->assertEquals(Yellow::class, $channels[2]);
        $this->assertEquals(Key::class, $channels[3]);
        $this->assertEquals(Alpha::class, $channels[4]);
    }
}
