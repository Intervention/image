<?php

use Intervention\Image\Gd\Gif\Frame;

class GifFrameTest extends PHPUnit_Framework_TestCase
{
    public function testPropertyIsSet()
    {
        $frame = new Frame;
        $this->assertFalse($frame->propertyIsSet('foo'));

        $frame = new Frame;
        $frame->foo = 'bar';
        $this->assertTrue($frame->propertyIsSet('foo'));

        $frame = new Frame;
        $frame->foo = false;
        $this->assertTrue($frame->propertyIsSet('foo'));
    }

    public function testSetProperty()
    {
        $frame = new Frame;
        $this->assertFalse($frame->propertyIsSet('foo'));
        $test = $frame->setProperty('foo', 'bar');
        $this->assertEquals('bar', $frame->foo);
        $this->assertTrue($frame->propertyIsSet('foo'));
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Frame', $test);
    }

    public function testSetGetImageData()
    {
        $frame = new Frame;
        $this->assertNull($frame->getImageData());
        $result = $frame->setImageData('foo');
        $this->assertEquals('foo', $frame->getImageData());
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Frame', $result);
    }

    public function testSetGetDelay()
    {
        $frame = new Frame;
        $this->assertFalse($frame->getDelay());

        $frame = new Frame;
        $result = $frame->setDelay(20);
        $this->assertEquals(20, $frame->getDelay());   
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Frame', $result);
    }

    public function testDecodeDelay()
    {
        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x00\x14\x00\x00\x00");
        $this->assertEquals(20, $frame->decodeDelay());
    }

    public function testHasTransparentColor()
    {
        $frame = new Frame;
        $this->assertFalse($frame->hasTransparentColor());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x01\x14\x00\x00\x00");
        $this->assertTrue($frame->hasTransparentColor());
    }

    public function testSetGetTransparentColorIndex()
    {
        $frame = new Frame;
        $this->assertFalse($frame->getTransparentColorIndex());

        $frame = new Frame;
        $result = $frame->setTransparentColorIndex('foo');
        $this->assertEquals('foo', $frame->getTransparentColorIndex());   
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Frame', $result);
    }

    public function testDecodeTransparentColorIndex()
    {
        $frame = new Frame;
        $this->assertFalse($frame->decodeTransparentColorIndex());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x01\x14\x00\xF1\x00");
        $this->assertEquals("\xF1", $frame->decodeTransparentColorIndex());
    }

    public function testSetGetDisposalMethod()
    {
        $frame = new Frame;
        $this->assertEquals(0, $frame->getDisposalMethod());

        $frame = new Frame;
        $result = $frame->setDisposalMethod('foo');
        $this->assertEquals('foo', $frame->getDisposalMethod());   
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Frame', $result);
    }

    public function testDecodeDisposalMethod()
    {
        $frame = new Frame;
        $this->assertEquals(0, $frame->decodeDisposalMethod());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x05\x14\x00\xF1\x00");
        $this->assertEquals(1, $frame->decodeDisposalMethod());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x0C\x14\x00\xF1\x00");
        $this->assertEquals(3, $frame->decodeDisposalMethod());
    }

    public function testDecodeWidth()
    {
        $frame = new Frame;
        $this->assertFalse($frame->decodeWidth());

        $frame = new Frame;
        $frame->setProperty('imageDescriptor', "\x00\x00\x00\x00\x40\x01\xF0\x00\x00");
        $this->assertEquals(320, $frame->decodeWidth());
    }

    public function testDecodeHeight()
    {
        $frame = new Frame;
        $this->assertFalse($frame->decodeHeight());

        $frame = new Frame;
        $frame->setProperty('imageDescriptor', "\x00\x00\x00\x00\x40\x01\xF0\x00\x00");
        $this->assertEquals(240, $frame->decodeHeight());
    }

    public function testGetSize()
    {
        $frame = new Frame;
        $frame->setProperty('imageDescriptor', "\x00\x00\x00\x00\x40\x01\xF0\x00\x00");
        $size = $frame->getSize();
        $this->assertInstanceOf('StdClass', $size);
        $this->assertEquals(320, $size->width);
        $this->assertEquals(240, $size->height);
    }

    public function testDecodeOffsetTop()
    {
        $frame = new Frame;
        $this->assertFalse($frame->decodeOffsetTop());

        $frame = new Frame;
        $frame->setProperty('imageDescriptor', "\x18\x00\x0C\x00\x40\x01\xF0\x00\x00");
        $this->assertEquals(12, $frame->decodeOffsetTop());
    }

    public function testDecodeOffsetLeft()
    {
        $frame = new Frame;
        $this->assertFalse($frame->decodeOffsetLeft());

        $frame = new Frame;
        $frame->setProperty('imageDescriptor', "\x18\x00\x0C\x00\x40\x01\xF0\x00\x00");
        $this->assertEquals(24, $frame->decodeOffsetLeft());
    }

    public function testGetOffset()
    {
        $frame = new Frame;
        $frame->setProperty('imageDescriptor', "\x18\x00\x0C\x00\x40\x01\xF0\x00\x00");
        $offset = $frame->getOffset();
        $this->assertInstanceOf('StdClass', $offset);
        $this->assertEquals(12, $offset->top);
        $this->assertEquals(24, $offset->left);
    }

    public function testSetInterlaced()
    {
        $frame = new Frame;
        $frame->setInterlaced(true);
        $this->assertTrue($frame->interlaced);
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Frame', $frame);
    }
}
