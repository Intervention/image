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

    public function testGetDelay()
    {
        $frame = new Frame;
        $this->assertFalse($frame->getDelay());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x00\x14\x00\x00\x00");
        $this->assertEquals(20, $frame->getDelay());
    }

    public function testHasTransparentColor()
    {
        $frame = new Frame;
        $this->assertFalse($frame->hasTransparentColor());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x01\x14\x00\x00\x00");
        $this->assertTrue($frame->hasTransparentColor());
    }

    public function testGetTransparentColorIndex()
    {
        $frame = new Frame;
        $this->assertFalse($frame->getTransparentColorIndex());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x01\x14\x00\xF1\x00");
        $this->assertEquals("\xF1", $frame->getTransparentColorIndex());
    }

    public function testGetDisposalMethod()
    {
        $frame = new Frame;
        $this->assertEquals(0, $frame->getDisposalMethod());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x05\x14\x00\xF1\x00");
        $this->assertEquals(1, $frame->getDisposalMethod());

        $frame = new Frame;
        $frame->setProperty('graphicsControlExtension', "\x04\x0C\x14\x00\xF1\x00");
        $this->assertEquals(3, $frame->getDisposalMethod());
    }
}
