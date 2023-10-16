<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Gd\ColorTransformer;
use Intervention\Image\Tests\TestCase;

class ColorTransformerTest extends TestCase
{
    public function testFromInteger(): void
    {
        $result = ColorTransformer::colorFromInteger(850736919);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([181, 55, 23, 155], $result->toArray());

        $result = ColorTransformer::colorFromInteger(16777215);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([255, 255, 255, 255], $result->toArray());
    }

    public function testToInteger(): void
    {
        $result = ColorTransformer::colorToInteger(new Color(181, 55, 23, 155));
        $this->assertEquals(850736919, $result);

        $result = ColorTransformer::colorToInteger(new Color(255, 255, 255, 255));
        $this->assertEquals(16777215, $result);
    }
}
