<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsl;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsl\Channels\Hue;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Colors\Hsl\Channels\Saturation;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Hsl\Colorspace;
use Intervention\Image\Colors\Hsl\Channels\Alpha;
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
        $result = $colorspace->colorFromNormalized([1, 0, 1]);
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(360, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Luminance::class)->value());
        $this->assertEquals(255, $result->channel(Alpha::class)->value());

        $colorspace = new Colorspace();
        $result = $colorspace->colorFromNormalized([1, 0, 1, .2]);
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(360, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Luminance::class)->value());
        $this->assertEquals(51, $result->channel(Alpha::class)->value());
    }

    public function testImportRgbColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new RgbColor(255, 0, 255));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(300, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());

        $result = $colorspace->importColor(new RgbColor(127, 127, 127));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());

        $result = $colorspace->importColor(new RgbColor(255, 0, 0, 0.3333333333));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());
    }

    public function testImportCmykColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new CmykColor(0, 100, 0, 0));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(300, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());

        $result = $colorspace->importColor(new CmykColor(0, 0, 0, 50));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());
    }

    public function testImportHsvColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new HsvColor(300, 100, 100));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(300, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());

        $result = $colorspace->importColor(new HsvColor(0, 0, 50));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());
    }

    public function testImportNamedColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(NamedColor::WHITE);
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Luminance::class)->value());
    }

    public function testImportOklabColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new OklabColor(0.68, 0.17, 0.14));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEqualsWithDelta(19, $result->channel(Hue::class)->value(), 2);
        $this->assertEqualsWithDelta(100, $result->channel(Saturation::class)->value(), 1);
        $this->assertEqualsWithDelta(50, $result->channel(Luminance::class)->value(), 2);
    }

    public function testImportOklchColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new OklchColor(0.68, 0.22, 38.8));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEqualsWithDelta(19, $result->channel(Hue::class)->value(), 2);
        $this->assertEqualsWithDelta(100, $result->channel(Saturation::class)->value(), 1);
        $this->assertEqualsWithDelta(50, $result->channel(Luminance::class)->value(), 2);
    }

    public function testImportHslColorPassthrough(): void
    {
        $colorspace = new Colorspace();

        // HslColor is not in the match statement, so it falls to default and throws
        $color = new HslColor(200, 50, 60);
        $this->expectException(NotSupportedException::class);
        $colorspace->importColor($color);
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
        $colorspace->colorFromNormalized([0.5, null, 0.5]);
    }

    public function testImportUnsupportedColor(): void
    {
        $colorspace = new Colorspace();
        $color = Mockery::mock(\Intervention\Image\Interfaces\ColorInterface::class);
        $this->expectException(NotSupportedException::class);
        $colorspace->importColor($color);
    }

    public function testImportHsvColorLuminanceZero(): void
    {
        $colorspace = new Colorspace();
        // HSV with saturation=100, value=0 -> luminance=0
        $result = $colorspace->importColor(new HsvColor(0, 100, 0));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(0, $result->channel(Luminance::class)->value());
    }

    public function testImportHsvColorLuminanceOne(): void
    {
        $colorspace = new Colorspace();
        // HSV with saturation=0, value=100 -> luminance=100 (=1 normalized)
        $result = $colorspace->importColor(new HsvColor(0, 0, 100));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(100, $result->channel(Luminance::class)->value());
    }

    public function testImportRgbColorGreenDominant(): void
    {
        $colorspace = new Colorspace();

        // RGB(0, 255, 0) => max=G => hits ($max == $g) branch in hue calculation
        $result = $colorspace->importColor(new RgbColor(0, 255, 0));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(120, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());
    }

    public function testImportRgbColorBlueDominant(): void
    {
        $colorspace = new Colorspace();

        // RGB(0, 0, 255) => max=B => hits ($max == $b) branch in hue calculation
        $result = $colorspace->importColor(new RgbColor(0, 0, 255));
        $this->assertInstanceOf(HslColor::class, $result);
        $this->assertEquals(240, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Luminance::class)->value());
    }
}
