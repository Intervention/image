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
 * @covers \Intervention\Image\Drivers\Imagick\Image
 */
class ImageTest extends TestCase
{
    protected Image $image;

    protected function setUp(): void
    {
        // create base image
        $imagick = new Imagick();

        // add frame
        $frame = new Imagick();
        $frame->newImage(3, 2, new ImagickPixel('red'), 'png');
        $imagick->addImage($frame);

        // add frame
        $frame = new Imagick();
        $frame->newImage(3, 2, new ImagickPixel('green'), 'png');
        $imagick->addImage($frame);

        // create intervention image
        $this->image = new Image($imagick);
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(Image::class, $this->image);
    }
    
    public function testGetFrame(): void
    {
        $this->assertInstanceOf(Frame::class, $this->image->getFrame());
        $this->assertInstanceOf(Frame::class, $this->image->getFrame(1));
        $this->assertNull($this->image->getFrame(2));
    }

    public function testAddFrame(): void
    {
        $frame = new Imagick();
        $frame->newImage(3, 2, new ImagickPixel('blue'), 'png');
        $frame = new Frame($frame);

        $this->assertCount(2, $this->image);
        $result = $this->image->addFrame($frame);
        $this->assertInstanceOf(Image::class, $result);
        $this->assertCount(3, $this->image);
    }

    public function testIterator(): void
    {
        foreach ($this->image as $frame) {
            $this->assertInstanceOf(Frame::class, $frame);
        }
    }

    public function testCount(): void
    {
        $this->assertEquals(2, $this->image->count());
        $this->assertEquals(2, count($this->image));
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
        $this->assertInstanceOf(Size::class, $this->image->getSize());
    }
}
