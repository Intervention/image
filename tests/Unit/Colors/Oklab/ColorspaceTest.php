<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklab;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Oklab\Color as OklabColor;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Oklab\Channels\A;
use Intervention\Image\Colors\Oklab\Channels\Alpha;
use Intervention\Image\Colors\Oklab\Channels\B;
use Intervention\Image\Colors\Oklab\Channels\Lightness;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Oklab\Colorspace;
use Intervention\Image\Colors\Rgb\NamedColor;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Colorspace::class)]
final class ColorspaceTest extends BaseTestCase
{
    public function testColorFromNormalized(): void
    {
        $colorspace = new Colorspace();

        $result = $colorspace->colorFromNormalized([1.0, 0.5, 0]);
        $this->assertInstanceOf(OklabColor::class, $result);
        $this->assertEquals(1.0, $result->channel(Lightness::class)->value());
        $this->assertEquals(0.0, $result->channel(A::class)->value());
        $this->assertEquals(-0.4, $result->channel(B::class)->value());

        $result = $colorspace->colorFromNormalized([1.0, 1, .25]);
        $this->assertInstanceOf(OklabColor::class, $result);
        $this->assertEquals(1.0, $result->channel(Lightness::class)->value());
        $this->assertEquals(0.4, $result->channel(A::class)->value());
        $this->assertEquals(-0.2, $result->channel(B::class)->value());

        $result = $colorspace->colorFromNormalized([1.0, 1, .25, .2]);
        $this->assertInstanceOf(OklabColor::class, $result);
        $this->assertEquals(1.0, $result->channel(Lightness::class)->value());
        $this->assertEquals(0.4, $result->channel(A::class)->value());
        $this->assertEquals(-0.2, $result->channel(B::class)->value());
        $this->assertEquals(51, $result->channel(Alpha::class)->value());
    }

    public function testImportRgbColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new RgbColor(255, 85, 0));
        $this->assertInstanceOf(OklabColor::class, $result);
        $this->assertEquals(0.68, round($result->channel(Lightness::class)->value(), 2));
        $this->assertEquals(0.17, round($result->channel(A::class)->value(), 2));
        $this->assertEquals(0.14, round($result->channel(B::class)->value(), 2));
    }

    public function testImportCmykColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new CmykColor(0, 67, 100, 0));
        $this->assertInstanceOf(OklabColor::class, $result);
        $this->assertEquals(0.7, round($result->channel(Lightness::class)->value(), 1));
        $this->assertEquals(0.2, round($result->channel(A::class)->value(), 1));
        $this->assertEquals(0.1, round($result->channel(B::class)->value(), 1));
    }

    public function testImportHsvColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new HsvColor(300, 100, 100));
        $this->assertInstanceOf(OklabColor::class, $result);
        $this->assertEquals(0.7, round($result->channel(Lightness::class)->value(), 1));
        $this->assertEquals(0.3, round($result->channel(A::class)->value(), 1));
        $this->assertEquals(-0.2, round($result->channel(B::class)->value(), 1));
    }

    public function testImportHslColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new HslColor(300, 100, 50));
        $this->assertInstanceOf(OklabColor::class, $result);
        $this->assertEquals(0.7, round($result->channel(Lightness::class)->value(), 1));
        $this->assertEquals(0.3, round($result->channel(A::class)->value(), 1));
        $this->assertEquals(-0.2, round($result->channel(B::class)->value(), 1));
    }

    public function testImportNamedColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(NamedColor::WHITE);
        $this->assertInstanceOf(OklabColor::class, $result);
        $this->assertEquals(1.0, round($result->channel(Lightness::class)->value(), 1));
        $this->assertEquals(0.0, round($result->channel(A::class)->value(), 1));
        $this->assertEquals(0.0, round($result->channel(B::class)->value(), 1));
    }
}
