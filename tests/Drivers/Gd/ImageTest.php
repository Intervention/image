<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Color;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;

class ImageTest extends TestCase
{
    protected Image $image;

    protected function setUp(): void
    {
        $gd1 = imagecreatetruecolor(3, 2);
        imagefill($gd1, 0, 0, imagecolorallocate($gd1, 255, 0, 0));
        $gd2 = imagecreatetruecolor(3, 2);
        imagefill($gd2, 0, 0, imagecolorallocate($gd1, 0, 255, 0));
        $gd3 = imagecreatetruecolor(3, 2);
        imagefill($gd3, 0, 0, imagecolorallocate($gd1, 0, 0, 255));
        $this->image = new Image(new Collection([
            new Frame($gd1),
            new Frame($gd2),
            new Frame($gd3),
        ]));
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(Image::class, $this->image);
    }

    public function testIterator(): void
    {
        foreach ($this->image as $frame) {
            $this->assertInstanceOf(Frame::class, $frame);
        }
    }

    public function testWidth(): void
    {
        $this->assertEquals(3, $this->image->width());
    }

    public function testHeight(): void
    {
        $this->assertEquals(2, $this->image->height());
    }

    public function testGetSize(): void
    {
        $this->assertInstanceOf(Size::class, $this->image->getSize());
    }

    public function testPickColor(): void
    {
        $color = $this->image->pickColor(0, 0);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(255, $color->red());
        $this->assertEquals(0, $color->green());
        $this->assertEquals(0, $color->blue());

        $color = $this->image->pickColor(0, 0, 1);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(0, $color->red());
        $this->assertEquals(255, $color->green());
        $this->assertEquals(0, $color->blue());

        $color = $this->image->pickColor(0, 0, 2);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(0, $color->red());
        $this->assertEquals(0, $color->green());
        $this->assertEquals(255, $color->blue());

        $color = $this->image->pickColor(0, 0, 3);
        $this->assertNull($color);
    }

    public function testPickColors(): void
    {
        $colors = $this->image->pickColors(0, 0);
        $this->assertInstanceOf(Collection::class, $colors);
        $this->assertCount(3, $colors);

        $this->assertEquals(255, $colors->get(0)->red());
        $this->assertEquals(0, $colors->get(0)->green());
        $this->assertEquals(0, $colors->get(0)->blue());

        $this->assertEquals(0, $colors->get(1)->red());
        $this->assertEquals(255, $colors->get(1)->green());
        $this->assertEquals(0, $colors->get(1)->blue());

        $this->assertEquals(0, $colors->get(2)->red());
        $this->assertEquals(0, $colors->get(2)->green());
        $this->assertEquals(255, $colors->get(2)->blue());
    }
}
