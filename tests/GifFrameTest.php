<?php

use Intervention\Image\Tools\Gif\Frame;

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
        $this->assertInstanceOf('Intervention\Image\Tools\Gif\Frame', $test);
    }

    public function testSetGetImageData()
    {
        $frame = new Frame;
        $this->assertNull($frame->getImageData());
        $result = $frame->setImageData('foo');
        $this->assertEquals('foo', $frame->getImageData());
        $this->assertInstanceOf('Intervention\Image\Tools\Gif\Frame', $result);
    }

    public function testSetGetDelay()
    {
        $frame = new Frame;
        $this->assertNull($frame->getDelay());

        $frame = new Frame;
        $result = $frame->setDelay(20);
        $this->assertEquals(20, $frame->getDelay());   
        $this->assertInstanceOf('Intervention\Image\Tools\Gif\Frame', $result);
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
        $this->assertNull($frame->getTransparentColorIndex());

        $frame = new Frame;
        $result = $frame->setTransparentColorIndex('foo');
        $this->assertEquals('foo', $frame->getTransparentColorIndex());   
        $this->assertInstanceOf('Intervention\Image\Tools\Gif\Frame', $result);
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
        $this->assertNull($frame->getDisposalMethod());

        $frame = new Frame;
        $result = $frame->setDisposalMethod('foo');
        $this->assertEquals('foo', $frame->getDisposalMethod());   
        $this->assertInstanceOf('Intervention\Image\Tools\Gif\Frame', $result);
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
}
