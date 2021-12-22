<?php

namespace Intervention\Image\Tests\Drivers\Imagick;

use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Drivers\Imagick\Color
 */
class ColorTest extends TestCase
{
    protected function getTestColor(int $r = 0, int $g = 0, int $b = 0, float $a = 1): Color
    {
        return new Color(
            new ImagickPixel(sprintf('rgba(%s, %s, %s, %s)', $r, $g, $b, $a))
        );
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
        $color = $this->getTestColor(0, 0, 120, 1);
        $this->assertEquals(1, $color->alpha());

        $color = $this->getTestColor(0, 0, 120, 0);
        $this->assertEquals(0, $color->alpha());

        $color = $this->getTestColor(0, 0, 120, .5);
        $this->assertEquals(.5, $color->alpha());

        $color = $this->getTestColor(0, 0, 120, .57);
        $this->assertEquals(.57, $color->alpha());

        $color = $this->getTestColor(0, 0, 120, .578);
        $this->assertEquals(.58, $color->alpha());
    }

    public function testToArray(): void
    {
        $color = $this->getTestColor(0, 0, 120, 1);
        $this->assertEquals([0, 0, 120, 1], $color->toArray());

        $color = $this->getTestColor(0, 0, 120, 0);
        $this->assertEquals([0, 0, 120, 0], $color->toArray());

        $color = $this->getTestColor(0, 0, 120, .5);
        $this->assertEquals([0, 0, 120, .5], $color->toArray());

        $color = $this->getTestColor(0, 0, 120, .57);
        $this->assertEquals([0, 0, 120, .57], $color->toArray());

        $color = $this->getTestColor(0, 0, 120, .578);
        $this->assertEquals([0, 0, 120, .58], $color->toArray());
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

    public function testToInt(): void
    {
        $color = $this->getTestColor(255, 255, 255);
        $this->assertEquals($color->toInt(), 4294967295);

        $color = $this->getTestColor(255, 255, 255, 1);
        $this->assertEquals($color->toInt(), 4294967295);

        $color = $this->getTestColor(181, 55, 23, 0.2);
        $this->assertEquals($color->toInt(), 867514135);

        $color = $this->getTestColor(255, 255, 255, 0.5);
        $this->assertEquals($color->toInt(), 2164260863);

        $color = $this->getTestColor(181, 55, 23, 1);
        $this->assertEquals($color->toInt(), 4290066199);

        $color = $this->getTestColor(0, 0, 0, 0);
        $this->assertEquals($color->toInt(), 0);
    }
}
