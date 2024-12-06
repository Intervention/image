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
use Intervention\Image\Colors\Hsv\Colorspace;
use Intervention\Image\Tests\BaseTestCase;

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

        $result = $colorspace->importColor(new RgbColor(127, 127, 127, 85));
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
}
