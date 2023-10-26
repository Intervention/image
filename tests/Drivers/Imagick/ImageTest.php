<?php

namespace Intervention\Image\Tests\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Profile;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Geometry\Rectangle;
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
        $this->expectException(AnimationException::class);
        $this->image->getFrame(2);
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
        $this->assertInstanceOf(Rectangle::class, $this->image->getSize());
    }

    public function testGetColorspace(): void
    {
        $imagick = new Imagick();
        $imagick->readImageBlob($this->getTestImageData('test.jpg'));
        $image = new Image($imagick);
        $this->assertInstanceOf(RgbColorspace::class, $image->getColorspace());

        $imagick = new Imagick();
        $imagick->readImageBlob($this->getTestImageData('cmyk.jpg'));
        $image = new Image($imagick);
        $this->assertInstanceOf(CmykColorspace::class, $image->getColorspace());
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

        $result = $this->image->setColorspace('cmyk');
        $this->assertInstanceOf(Image::class, $result);
        $this->assertInstanceOf(CmykColorspace::class, $result->getColorspace());

        $result = $this->image->setColorspace(CmykColorspace::class);
        $this->assertInstanceOf(Image::class, $result);
        $this->assertInstanceOf(CmykColorspace::class, $result->getColorspace());

        $result = $this->image->setColorspace(new CmykColorspace());
        $this->assertInstanceOf(Image::class, $result);
        $this->assertInstanceOf(CmykColorspace::class, $result->getColorspace());
    }

    public function testSetGetProfile(): void
    {
        $imagick = new Imagick();
        $imagick->readImageBlob($this->getTestImageData('test.jpg'));
        $image = new Image($imagick);
        $result = $image->profile();
        $this->assertInstanceOf(Profile::class, $result);
        $result = $image->setProfile($result);
        $this->assertInstanceOf(Image::class, $result);
    }

    public function testWithoutProfile(): void
    {
        $imagick = new Imagick();
        $imagick->readImageBlob($this->getTestImageData('test.jpg'));
        $image = new Image($imagick);
        $result = $image->withoutProfile();
        $this->assertInstanceOf(Image::class, $result);
        $this->expectException(ColorException::class);
        $image->profile();
    }
}
