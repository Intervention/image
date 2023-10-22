<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Exceptions\NotSupportedException;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Image
 */
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

    public function testCount(): void
    {
        $this->assertEquals(3, $this->image->count());
        $this->assertEquals(3, count($this->image));
    }

    public function testIterator(): void
    {
        foreach ($this->image as $frame) {
            $this->assertInstanceOf(Frame::class, $frame);
        }
    }

    public function testGetFrame(): void
    {
        $this->assertInstanceOf(Frame::class, $this->image->getFrame());
        $this->assertInstanceOf(Frame::class, $this->image->getFrame(1));
    }

    public function testAddFrame(): void
    {
        $this->assertCount(3, $this->image);
        $result = $this->image->addFrame(new Frame(imagecreatetruecolor(3, 2)));
        $this->assertInstanceOf(Image::class, $result);
        $this->assertCount(4, $this->image);
    }

    public function testSetGetLoops(): void
    {
        $this->assertEquals(0, $this->image->getLoops());
        $result = $this->image->setLoops(12);
        $this->assertEquals(12, $this->image->getLoops());
        $this->assertInstanceOf(Image::class, $result);
    }

    public function testIsAnimated(): void
    {
        $this->assertTrue($this->image->isAnimated());
    }

    public function testWidth(): void
    {
        $this->assertEquals(3, $this->image->getWidth());
    }

    public function testHeight(): void
    {
        $this->assertEquals(2, $this->image->getHeight());
    }

    public function testGetSize(): void
    {
        $this->assertInstanceOf(Rectangle::class, $this->image->getSize());
    }

    public function testPickColor(): void
    {
        $color = $this->image->pickColor(0, 0);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([255, 0, 0, 255], $color->toArray());

        $color = $this->image->pickColor(0, 0, 1);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([0, 255, 0, 255], $color->toArray());

        $color = $this->image->pickColor(0, 0, 2);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([0, 0, 255, 255], $color->toArray());

        $color = $this->image->pickColor(0, 0, 3);
        $this->assertNull($color);
    }

    public function testPickColors(): void
    {
        $colors = $this->image->pickColors(0, 0);
        $this->assertInstanceOf(Collection::class, $colors);
        $this->assertCount(3, $colors);
        $this->assertEquals([255, 0, 0, 255], $colors->get(0)->toArray());
        $this->assertEquals([0, 255, 0, 255], $colors->get(1)->toArray());
        $this->assertEquals([0, 0, 255, 255], $colors->get(2)->toArray());
    }

    public function testGetColorspace(): void
    {
        $this->assertInstanceOf(RgbColorspace::class, $this->image->getColorspace());
    }

    public function testSetColorspace(): void
    {
        $result = $this->image->setColorspace('rgb');
        $this->assertInstanceOf(Image::class, $result);
        $this->assertInstanceOf(RgbColorspace::class, $result->getColorspace());

        $result = $this->image->setColorspace(RgbColorspace::class);
        $this->assertInstanceOf(Image::class, $result);
        $this->assertInstanceOf(RgbColorspace::class, $result->getColorspace());

        $result = $this->image->setColorspace(new RgbColorspace());
        $this->assertInstanceOf(Image::class, $result);
        $this->assertInstanceOf(RgbColorspace::class, $result->getColorspace());

        $this->expectException(NotSupportedException::class);
        $this->image->setColorspace('cmyk');

        $this->expectException(NotSupportedException::class);
        $this->image->setColorspace(CmykColorspace::class);

        $this->expectException(NotSupportedException::class);
        $this->image->setColorspace(new CmykColorspace());
    }
}
