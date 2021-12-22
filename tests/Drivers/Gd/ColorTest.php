<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Drivers\Gd\Color;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Drivers\Gd\Color
 */
class ColorTest extends TestCase
{
    protected function getTestColor($r = 0, $g = 0, $b = 0, $a = 0): Color
    {
        $gd = imagecreatetruecolor(1, 1);
        $value = imagecolorallocatealpha($gd, $r, $g, $b, $a);

        return new Color($value);
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(Color::class, $this->getTestColor());
    }

    public function testRed(): void
    {
        $color = $this->getTestColor(255, 0, 0);
        $this->assertEquals(255, $color->red());
    }

    public function testGreen(): void
    {
        $color = $this->getTestColor(0, 150, 0);
        $this->assertEquals(150, $color->green());
    }

    public function testBlue(): void
    {
        $color = $this->getTestColor(0, 0, 120);
        $this->assertEquals(120, $color->blue());
    }

    public function testAlpha(): void
    {
        $color = $this->getTestColor(0, 0, 120, 0);
        $this->assertEquals(1, $color->alpha());

        $color = $this->getTestColor(0, 0, 120, 127);
        $this->assertEquals(0, $color->alpha());

        $color = $this->getTestColor(0, 0, 120, 64);
        $this->assertEquals(.5, $color->alpha());
    }

    public function testToArray(): void
    {
        $color = $this->getTestColor(0, 0, 120, 0);
        $this->assertEquals([0, 0, 120, 1], $color->toArray());

        $color = $this->getTestColor(0, 0, 120, 127);
        $this->assertEquals([0, 0, 120, 0], $color->toArray());

        $color = $this->getTestColor(0, 0, 120, 64);
        $this->assertEquals([0, 0, 120, .5], $color->toArray());
    }

    public function testToInt(): void
    {
        $color = $this->getTestColor(0, 0, 0, 0);
        $this->assertEquals(0, $color->toInt());

        $color = $this->getTestColor(255, 255, 255, 0);
        $this->assertEquals(16777215, $color->toInt());
    }

    public function testToHex(): void
    {
        $color = $this->getTestColor(181, 55, 23);
        $this->assertEquals('b53717', $color->toHex());
        $this->assertEquals('#b53717', $color->toHex('#'));

        $color = $this->getTestColor(181, 55, 23, 127);
        $this->assertEquals('b53717', $color->toHex());
        $this->assertEquals('#b53717', $color->toHex('#'));
    }
}
