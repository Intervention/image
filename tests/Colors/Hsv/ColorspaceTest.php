<?php

namespace Intervention\Image\Tests\Colors\Hsv;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsv\Channels\Hue;
use Intervention\Image\Colors\Hsv\Channels\Saturation;
use Intervention\Image\Colors\Hsv\Channels\Value;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Hsv\Colorspace;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Hsv\Colorspace
 */
class ColorspaceTest extends TestCase
{
    public function testConvertRgbColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->convertColor(new RgbColor(26, 26, 128));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(240, $result->channel(Hue::class)->value());
        $this->assertEquals(80, $result->channel(Saturation::class)->value());
        $this->assertEquals(50, $result->channel(Value::class)->value());
    }

    public function testConvertCmykColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->convertColor(new CmykColor(100, 0, 100, 0));
        $this->assertInstanceOf(HsvColor::class, $result);
        $this->assertEquals(120, $result->channel(Hue::class)->value());
        $this->assertEquals(100, $result->channel(Saturation::class)->value());
        $this->assertEquals(100, $result->channel(Value::class)->value());
    }
}
