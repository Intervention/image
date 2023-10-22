<?php

namespace Intervention\Image\Tests\Colors\Rgb;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgb\Colorspace
 */
class ColorspaceTest extends TestCase
{
    public function testConvertColor(): void
    {
        $colorspace = new Colorspace();
        $this->assertInstanceOf(
            RgbColor::class,
            $colorspace->convertColor(
                new CmykColor(0, 0, 0, 0)
            )
        );
    }
}
