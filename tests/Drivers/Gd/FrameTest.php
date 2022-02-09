<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use GdImage;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Frame
 */
class FrameTest extends TestCase
{
    protected function getTestFrame(): Frame
    {
        return new Frame(imagecreatetruecolor(3, 2));
    }

    public function testConstructor(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Frame::class, $frame);
    }

    public function testGetCore(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(GdImage::class, $frame->getCore());
    }

    public function testSetCore(): void
    {
        $core1 = imagecreatetruecolor(3, 2);
        $core2 = imagecreatetruecolor(3, 3);
        $frame = new Frame($core1);
        $this->assertEquals(2, $frame->getSize()->getHeight());
        $result = $frame->setCore($core2);
        $this->assertInstanceOf(Frame::Class, $result);
        $this->assertEquals(3, $frame->getSize()->getHeight());
    }

    public function testGetSize(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Size::class, $frame->getSize());
    }

    public function testSetGetDelay()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->getDelay());

        $result = $frame->setDelay(1.5);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(1.5, $frame->getDelay());
    }

    public function testSetGetDispose()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(1, $frame->getDispose());

        $result = $frame->setDispose(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getDispose());
    }

    public function testSetGetOffsetLeft()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->getOffsetLeft());

        $result = $frame->setOffsetLeft(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getOffsetLeft());
    }

    public function testSetGetOffsetTop()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->getOffsetTop());

        $result = $frame->setOffsetTop(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getOffsetTop());
    }

    public function testSetGetOffset()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->getOffsetTop());
        $this->assertEquals(0, $frame->getOffsetLeft());

        $result = $frame->setOffset(100, 200);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getOffsetLeft());
        $this->assertEquals(200, $frame->getOffsetTop());
    }

    public function testToImage(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Image::class, $frame->toImage());
    }
}
