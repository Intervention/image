<?php

namespace Intervention\Image\Tests\Drivers\Imagick;

use ImagickPixel;
use Intervention\Image\Colors\Rgba\Color;
use Intervention\Image\Drivers\Imagick\ColorTransformer;
use Intervention\Image\Tests\TestCase;

class ColorTransformerTest extends TestCase
{
    public function testFromPixel(): void
    {
        $result = ColorTransformer::colorFromPixel(new ImagickPixel('rgba(181, 55, 23, .6)'));
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([181, 55, 23, 153], $result->toArray());

        $result = ColorTransformer::colorFromPixel(new ImagickPixel('rgba(255, 255, 255, 1)'));
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([255, 255, 255, 255], $result->toArray());
    }

    public function testToPixel(): void
    {
        $result = ColorTransformer::colorToPixel(new Color(181, 55, 23, 153));
        $this->assertInstanceOf(ImagickPixel::class, $result);
        $this->assertEquals('srgba(181,55,23,0.6)', $result->getColorAsString());

        $result = ColorTransformer::colorToPixel(new Color(255, 255, 255, 255));
        $this->assertInstanceOf(ImagickPixel::class, $result);
        $this->assertEquals('srgba(255,255,255,1)', $result->getColorAsString());

        $result = ColorTransformer::colorToPixel(new Color(255, 255, 255, 170));
        $this->assertInstanceOf(ImagickPixel::class, $result);
        $this->assertEquals('srgba(255,255,255,0.699992)', $result->getColorAsString());
    }
}
