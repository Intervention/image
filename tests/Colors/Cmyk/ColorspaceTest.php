<?php

namespace Intervention\Image\Tests\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Cmyk\Colorspace;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Cmyk\Colorspace
 */
class ColorspaceTest extends TestCase
{
    public function testConvertColor(): void
    {
        $colorspace = new Colorspace();
        $this->assertInstanceOf(
            CmykColor::class,
            $colorspace->convertColor(
                new RgbColor(0, 0, 0)
            )
        );
    }
}
