<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklch;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklch\Color as OklchColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Oklch\Channels\Chroma;
use Intervention\Image\Colors\Oklch\Channels\Hue;
use Intervention\Image\Colors\Oklch\Channels\Lightness;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Oklch\Colorspace;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Colorspace::class)]
final class ColorspaceTest extends BaseTestCase
{
    public function testColorFromNormalized(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->colorFromNormalized([1.0, 0, 1]);
        $this->assertInstanceOf(OklchColor::class, $result);
        $this->assertEquals(1.0, $result->channel(Lightness::class)->value());
        $this->assertEquals(-0.4, $result->channel(Chroma::class)->value());
        $this->assertEquals(360, $result->channel(Hue::class)->value());

        $result = $colorspace->colorFromNormalized([0.5, 0.5, 0.25]);
        $this->assertInstanceOf(OklchColor::class, $result);
        $this->assertEquals(0.5, $result->channel(Lightness::class)->value());
        $this->assertEquals(0.0, $result->channel(Chroma::class)->value());
        $this->assertEquals(90.0, $result->channel(Hue::class)->value());
    }

    public function testImportRgbColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new RgbColor(255, 85, 0));
        $this->assertInstanceOf(OklchColor::class, $result);
        $this->assertEquals(0.68, round($result->channel(Lightness::class)->value(), 2));
        $this->assertEquals(0.22, round($result->channel(Chroma::class)->value(), 2));
        $this->assertEquals(38.8, round($result->channel(Hue::class)->value(), 2));
    }

    public function testImportOklabColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new OklabColor(.64905124115, .19974263609074, .13044605841927));
        $this->assertInstanceOf(OklchColor::class, $result);
        $this->assertEquals(.64905124115, $result->channel(Lightness::class)->value());
        $this->assertEquals(.23856507462242119, $result->channel(Chroma::class)->value());
        $this->assertEquals(33.14737546449335, $result->channel(Hue::class)->value());
    }

    public function testImportCmykColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new CmykColor(0, 67, 100, 0)); // ff5500, rgb(255, 85, 0)
        $this->assertInstanceOf(OklchColor::class, $result);
        $this->assertEquals(0.674822658543154, $result->channel(Lightness::class)->value());
        $this->assertEquals(0.2182398312296448, $result->channel(Chroma::class)->value());
        $this->assertEquals(38.56205123589542, $result->channel(Hue::class)->value());
    }

    public function testImportHsvColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new HsvColor(20, 100, 100));
        $this->assertInstanceOf(OklchColor::class, $result);
        $this->assertEquals(0.68, round($result->channel(Lightness::class)->value(), 2));
        $this->assertEquals(0.22, round($result->channel(Chroma::class)->value(), 2));
        $this->assertEquals(38.8, round($result->channel(Hue::class)->value(), 2));
    }

    public function testImportHslColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new HslColor(20, 100, 50));
        $this->assertInstanceOf(OklchColor::class, $result);
        $this->assertEquals(0.68, round($result->channel(Lightness::class)->value(), 2));
        $this->assertEquals(0.22, round($result->channel(Chroma::class)->value(), 2));
        $this->assertEquals(38.8, round($result->channel(Hue::class)->value(), 2));
    }
}
