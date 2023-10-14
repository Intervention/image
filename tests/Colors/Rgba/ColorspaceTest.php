<?php

namespace Intervention\Image\Tests\Colors\Rgba;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgba\Color as RgbaColor;
use Intervention\Image\Colors\Rgba\Colorspace;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgba\Colorspace
 */
class ColorspaceTest extends TestCase
{
    public function testConvertColor(): void
    {
        $colorspace = new Colorspace();
        $this->assertInstanceOf(
            RgbaColor::class,
            $colorspace->convertColor(
                new CmykColor(0, 0, 0, 0)
            )
        );
    }
}
