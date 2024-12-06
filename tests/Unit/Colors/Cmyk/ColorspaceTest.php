<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Cmyk;

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
use Intervention\Image\Tests\BaseTestCase;

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

        $result = $colorspace->importColor(new RgbColor(127, 127, 127, 85));
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals(0, $result->channel(Cyan::class)->value());
        $this->assertEquals(0, $result->channel(Magenta::class)->value());
        $this->assertEquals(0, $result->channel(Yellow::class)->value());
        $this->assertEquals(50, $result->channel(Key::class)->value());
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
}
