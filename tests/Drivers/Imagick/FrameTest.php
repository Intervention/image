<?php

namespace Intervention\Image\Tests\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 */
class FrameTest extends TestCase
{
    protected function getTestFrame(): Frame
    {
        $imagick = new Imagick();
        $imagick->newImage(3, 2, new ImagickPixel('red'), 'png');
        $imagick->setImageDelay(125); // 1.25 seconds
        $imagick->setImageDispose(5);
        $imagick->setImagePage(3, 2, 8, 9);

        return new Frame($imagick);
    }

    public function testConstructor(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Frame::class, $frame);
    }

    public function testGetSize(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Size::class, $frame->getSize());
    }

    public function testSetGetDelay()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(1.25, $frame->getDelay());

        $result = $frame->setDelay(2.5);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(2.5, $frame->getDelay());
        $this->assertEquals(250, $frame->getCore()->getImageDelay());
    }

    public function testSetGetDispose()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(5, $frame->getDispose());

        $result = $frame->setDispose(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getDispose());
    }

    public function testSetGetOffsetLeft()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(8, $frame->getOffsetLeft());

        $result = $frame->setOffsetLeft(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getOffsetLeft());
    }

    public function testSetGetOffsetTop()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(9, $frame->getOffsetTop());

        $result = $frame->setOffsetTop(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getOffsetTop());
    }

    public function testSetGetOffset()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(8, $frame->getOffsetLeft());
        $this->assertEquals(9, $frame->getOffsetTop());

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
