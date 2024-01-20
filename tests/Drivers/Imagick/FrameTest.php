<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Image;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Frame
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
        $this->assertInstanceOf(Rectangle::class, $frame->size());
    }

    public function testSetGetDelay()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(1.25, $frame->delay());

        $result = $frame->setDelay(2.5);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(2.5, $frame->delay());
        $this->assertEquals(250, $frame->native()->getImageDelay());
    }

    public function testSetGetDispose()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(5, $frame->dispose());

        $result = $frame->setDispose(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->dispose());
    }

    public function testSetGetOffsetLeft()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(8, $frame->offsetLeft());

        $result = $frame->setOffsetLeft(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->offsetLeft());
    }

    public function testSetGetOffsetTop()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(9, $frame->offsetTop());

        $result = $frame->setOffsetTop(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->offsetTop());
    }

    public function testSetGetOffset()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(8, $frame->offsetLeft());
        $this->assertEquals(9, $frame->offsetTop());

        $result = $frame->setOffset(100, 200);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->offsetLeft());
        $this->assertEquals(200, $frame->offsetTop());
    }

    public function testToImage(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Image::class, $frame->toImage(new Driver()));
    }
}
