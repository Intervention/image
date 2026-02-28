<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsv;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsv\Channels\Hue;
use Intervention\Image\Colors\Hsv\Channels\Saturation;
use Intervention\Image\Colors\Hsv\Channels\Value;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsv\Channels\Alpha;
use Intervention\Image\Colors\Hsv\Colorspace;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
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
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(360, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());

        $colorspace = new Colorspace();
        $result = $colorspace->colorFromNormalized([1, 0, 1, .2]);
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(360, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());
        $this->assertEquals(51, $result->channel(Alpha::class)->value());
    }

    public function testImportRgbColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new RgbColor(255, 0, 255));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(300, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());

        $result = $colorspace->importColor(new RgbColor(127, 127, 127));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Value::class)->value());

        $result = $colorspace->importColor(new RgbColor(127, 127, 127, .3333333333));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Value::class)->value());
    }

    public function testImportCmykColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new CmykColor(0, 100, 0, 0));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(300, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());

        $result = $colorspace->importColor(new CmykColor(0, 0, 0, 50));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Value::class)->value());
    }

    public function testImportHslColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new HslColor(300, 100, 50));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(300, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());

        $result = $colorspace->importColor(new HslColor(0, 0, 50));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Value::class)->value());
    }

    public function testImportNamedColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(NamedColor::WHITE);
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());
    }

    public function testImportOklabColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new OklabColor(0.68, 0.17, 0.14));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEqualsWithDelta(19, $result->channel(Hue::class)->value(), 2);
        $this->assertEqualsWithDelta(100, $result->channel(Saturation::class)->value(), 1);
        $this->assertEqualsWithDelta(100, $result->channel(Value::class)->value(), 1);
    }

    public function testImportOklchColor(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->importColor(new OklchColor(0.68, 0.22, 38.8));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEqualsWithDelta(19, $result->channel(Hue::class)->value(), 2);
        $this->assertEqualsWithDelta(100, $result->channel(Saturation::class)->value(), 1);
        $this->assertEqualsWithDelta(100, $result->channel(Value::class)->value(), 1);
    }

    public function testImportHsvColorPassthrough(): void
    {
        $colorspace = new Colorspace();

        $color = new HsvColor(200, 50, 80);
        $result = $colorspace->importColor($color);
        $this->assertInstanceOf(HsvColor::class, $result);
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

    public function testChannels(): void
    {
        $channels = Colorspace::channels();
        $this->assertIsArray($channels);
        $this->assertCount(4, $channels);
        $this->assertEquals(Hue::class, $channels[0]);
        $this->assertEquals(Saturation::class, $channels[1]);
        $this->assertEquals(Value::class, $channels[2]);
        $this->assertEquals(Alpha::class, $channels[3]);
    }

    public function testImportRgbColorHueBranchMaxIsGreen(): void
    {
        $colorspace = new Colorspace();

        // RGB(0, 255, 0) => max=G, min=R => hits default branch ($g == $min is false)
        $result = $colorspace->importColor(new RgbColor(0, 255, 0));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(120, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());
    }

    public function testImportRgbColorHueBranchMaxIsBlue(): void
    {
        $colorspace = new Colorspace();

        // RGB(0, 0, 255) => max=B, min=R => hits ($r == $min) branch
        $result = $colorspace->importColor(new RgbColor(0, 0, 255));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(240, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());
    }

    public function testImportRgbColorHueBranchMaxIsRed(): void
    {
        $colorspace = new Colorspace();

        // RGB(255, 0, 0) => max=R, min=G=B => hits ($b == $min) branch
        $result = $colorspace->importColor(new RgbColor(255, 0, 0));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());
    }

    public function testImportRgbColorGrayscale(): void
    {
        $colorspace = new Colorspace();

        // Grayscale color => chroma == 0 => early return
        $result = $colorspace->importColor(new RgbColor(100, 100, 100));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(39, $result->channel(Value::class)->value());
    }

    public function testImportHslColorBlack(): void
    {
        $colorspace = new Colorspace();

        // HslColor(0, 0, 0) = black => v = l + s*min(l, 1-l) = 0
        // hits ($v == 0) ? 0 : ... branch for saturation calculation
        $result = $colorspace->importColor(new HslColor(0, 0, 0));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(0, $result->channel(Hue::class)->value());
        $this->assertEquals(0, $result->channel(Saturation::class)->value());
        $this->assertEquals(0, $result->channel(Value::class)->value());
    }

    public function testImportHslColorTypeCheck(): void
    {
        $colorspace = new Colorspace();

        // Call protected importHslColor() with a non-HslColor to trigger type check
        $method = new \ReflectionMethod($colorspace, 'importHslColor');
        $method->setAccessible(true);

        $this->expectException(InvalidArgumentException::class);
        $method->invoke($colorspace, new RgbColor(255, 0, 0));
    }
}
