<?php

namespace Intervention\Image\Tests\Colors\Rgb;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Hsv\Color as HsvColor;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgb\Colorspace
 */
class ColorspaceTest extends TestCase
{
    public function testConvertCmykColor(): void
    {
        $colorspace = new Colorspace();
        $this->assertInstanceOf(
            RgbColor::class,
            $colorspace->importColor(
                new CmykColor(0, 0, 0, 0)
            )
        );
    }

    public function testConvertHsvColor(): void
    {
        $colorspace = new Colorspace();
        $result = $colorspace->importColor(new HsvColor(240, 80, 50));
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals(26, $result->channel(Red::class)->value());
        $this->assertEquals(26, $result->channel(Green::class)->value());
        $this->assertEquals(128, $result->channel(Blue::class)->value());
    }
}
