<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Traits;

use Imagick;
use ImagickPixel;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Imagick\Traits\CanHandleColors;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Traits\CanHandleColors
 */
class CanHandleColorsTest extends TestCase
{
    protected function getAnonymousTrait()
    {
        return new class()
        {
            use CanHandleColors;
        };
    }

    public function testColorFromPixel(): void
    {
        $result = $this->getAnonymousTrait()
            ->pixelToColor(new ImagickPixel(), new RgbColorspace());
        $this->assertInstanceOf(RgbColor::class, $result);

        $result = $this->getAnonymousTrait()
            ->pixelToColor(new ImagickPixel('rgba(10, 20, 30, .2)'), new RgbColorspace());
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals([10, 20, 30, 51], $result->toArray());

        $result = $this->getAnonymousTrait()
            ->pixelToColor(new ImagickPixel('cmyk(10%, 20%, 30%, 40%)'), new CmykColorspace());
        $this->assertInstanceOf(CmykColor::class, $result);
        $this->assertEquals([10, 20, 30, 40], $result->toArray());
    }

    public function testColorToPixel(): void
    {
        $result = $this->getAnonymousTrait()->colorToPixel(new RgbColor(10, 20, 30), new RgbColorspace());
        $this->assertInstanceOf(ImagickPixel::class, $result);
        $this->assertEquals('srgb(10,20,30)', $result->getColorAsString());

        $result = $this->getAnonymousTrait()->colorToPixel(new CmykColor(100, 50, 25, 0), new CmykColorspace());
        $this->assertInstanceOf(ImagickPixel::class, $result);
        $this->assertEquals(1, $result->getColorValue(Imagick::COLOR_CYAN));
        $this->assertEquals(.5, round($result->getColorValue(Imagick::COLOR_MAGENTA), 2));
        $this->assertEquals(.25, round($result->getColorValue(Imagick::COLOR_YELLOW), 2));
        $this->assertEquals(0, $result->getColorValue(Imagick::COLOR_BLACK));
    }
}
