<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Colors\Rgb\NamedColor;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

#[CoversClass(Colorspace::class)]
final class ColorspaceTest extends BaseTestCase
{
    public function testColorFromNormalized(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->colorFromNormalized([1, 0, 1]);
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(0, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());
        $this->assertEquals(255, $result->channel(Alpha::class)->value());

        $result = $colorspace->colorFromNormalized([1, 0, 1, .2]);
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(0, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());
        $this->assertEquals(51, $result->channel(Alpha::class)->value());
    }

    public function testImportCmykColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new CmykColor(0, 100, 0, 0));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(0, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());

        $result = $colorspace->importColor(new CmykColor(0, 0, 0, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(127, $result->channel(Red::class)->value());
        $this->assertEquals(127, $result->channel(Green::class)->value());
        $this->assertEquals(127, $result->channel(Blue::class)->value());
    }

    public function testImportHsvColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new HsvColor(300, 100, 100));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(0, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());

        $result = $colorspace->importColor(new HsvColor(0, 0, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(128, $result->channel(Red::class)->value());
        $this->assertEquals(128, $result->channel(Green::class)->value());
        $this->assertEquals(128, $result->channel(Blue::class)->value());
    }

    public function testImportHsvColorHueBranch1(): void
    {
        $colorspace = new Colorspace();

        // hue=30 => normalized*6 < 1 => first match branch [chroma, x, 0]
        $result = $colorspace->importColor(new HsvColor(30, 100, 100));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(128, $result->channel(Green::class)->value());
        $this->assertEquals(0, $result->channel(Blue::class)->value());
    }

    public function testImportHsvColorHueBranch2(): void
    {
        $colorspace = new Colorspace();

        // hue=90 => normalized*6 < 2 => second match branch [x, chroma, 0]
        $result = $colorspace->importColor(new HsvColor(90, 100, 100));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(128, $result->channel(Red::class)->value());
        $this->assertEquals(255, $result->channel(Green::class)->value());
        $this->assertEquals(0, $result->channel(Blue::class)->value());
    }

    public function testImportHsvColorHueBranch3(): void
    {
        $colorspace = new Colorspace();

        // hue=150 => normalized*6 < 3 => third match branch [0, chroma, x]
        $result = $colorspace->importColor(new HsvColor(150, 100, 100));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(0, $result->channel(Red::class)->value());
        $this->assertEquals(255, $result->channel(Green::class)->value());
        $this->assertEquals(128, $result->channel(Blue::class)->value());
    }

    public function testImportHsvColorHueBranch4(): void
    {
        $colorspace = new Colorspace();

        // hue=210 => normalized*6 < 4 => fourth match branch [0, x, chroma]
        $result = $colorspace->importColor(new HsvColor(210, 100, 100));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(0, $result->channel(Red::class)->value());
        $this->assertEquals(128, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());
    }

    public function testImportHsvColorHueBranch5(): void
    {
        $colorspace = new Colorspace();

        // hue=270 => normalized*6 < 5 => fifth match branch [x, 0, chroma]
        $result = $colorspace->importColor(new HsvColor(270, 100, 100));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(128, $result->channel(Red::class)->value());
        $this->assertEquals(0, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());
    }

    public function testImportHslColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new HslColor(300, 100, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(0, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());

        $result = $colorspace->importColor(new HslColor(0, 0, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(128, $result->channel(Red::class)->value());
        $this->assertEquals(128, $result->channel(Green::class)->value());
        $this->assertEquals(128, $result->channel(Blue::class)->value());
    }

    public function testImportHslColorHueBranch1(): void
    {
        $colorspace = new Colorspace();

        // hue=30 => h < 1/6 => first match branch [c, x, 0]
        $result = $colorspace->importColor(new HslColor(30, 100, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(128, $result->channel(Green::class)->value());
        $this->assertEquals(0, $result->channel(Blue::class)->value());
    }

    public function testImportHslColorHueBranch2(): void
    {
        $colorspace = new Colorspace();

        // hue=90 => h < 2/6 => second match branch [x, c, 0]
        $result = $colorspace->importColor(new HslColor(90, 100, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(128, $result->channel(Red::class)->value());
        $this->assertEquals(255, $result->channel(Green::class)->value());
        $this->assertEquals(0, $result->channel(Blue::class)->value());
    }

    public function testImportHslColorHueBranch3(): void
    {
        $colorspace = new Colorspace();

        // hue=150 => h < 3/6 => third match branch [0, c, x]
        $result = $colorspace->importColor(new HslColor(150, 100, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(0, $result->channel(Red::class)->value());
        $this->assertEquals(255, $result->channel(Green::class)->value());
        $this->assertEquals(128, $result->channel(Blue::class)->value());
    }

    public function testImportHslColorHueBranch4(): void
    {
        $colorspace = new Colorspace();

        // hue=210 => h < 4/6 => fourth match branch [0, x, c]
        $result = $colorspace->importColor(new HslColor(210, 100, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(0, $result->channel(Red::class)->value());
        $this->assertEquals(128, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());
    }

    public function testImportHslColorHueBranch5(): void
    {
        $colorspace = new Colorspace();

        // hue=270 => h < 5/6 => fifth match branch [x, 0, c]
        $result = $colorspace->importColor(new HslColor(270, 100, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(128, $result->channel(Red::class)->value());
        $this->assertEquals(0, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());
    }

    public function testImportNamedColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(NamedColor::STEELBLUE);
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(70, $result->channel(Red::class)->value());
        $this->assertEquals(130, $result->channel(Green::class)->value());
        $this->assertEquals(180, $result->channel(Blue::class)->value());
    }

    public function testImportOklabColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new OklabColor(0.68, 0.17, 0.14));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEqualsWithDelta(255, $result->channel(Red::class)->value(), 2);
        $this->assertEqualsWithDelta(85, $result->channel(Green::class)->value(), 2);
        $this->assertEqualsWithDelta(0, $result->channel(Blue::class)->value(), 2);
    }

    public function testImportOklchColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new OklchColor(0.68, 0.22, 38.8));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEqualsWithDelta(255, $result->channel(Red::class)->value(), 2);
        $this->assertEqualsWithDelta(83, $result->channel(Green::class)->value(), 2);
        $this->assertEqualsWithDelta(0, $result->channel(Blue::class)->value(), 2);
    }

    public function testImportRgbColorPassthrough(): void
    {
        $colorspace = new Colorspace();

        $color = new RgbColor(100, 150, 200);
        $result = $colorspace->importColor($color);
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertSame($color, $result);
    }

    public function testColorFromNormalizedInvalidChannelCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Colorspace::colorFromNormalized([0.5, 0.5]);
    }

    public function testColorFromNormalizedWithNullValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Colorspace::colorFromNormalized([0.5, null, 0.5]);
    }

    public function testImportUnsupportedColor(): void
    {
        $this->expectException(NotSupportedException::class);
        $colorspace = new Colorspace();
        $colorspace->importColor(Mockery::mock(ColorInterface::class));
    }
}
