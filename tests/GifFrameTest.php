<?php

use Intervention\Image\Tools\Gif\Frame;

class GifFrameTest extends PHPUnit_Framework_TestCase
{
    public function testHasProperty()
    {
        $frame = new Frame;
        $this->assertFalse($frame->hasProperty('foo'));

        $frame = new Frame;
        $frame->foo = 'bar';
        $this->assertTrue($frame->hasProperty('foo'));

        $frame = new Frame;
        $frame->foo = null;
        $this->assertTrue($frame->hasProperty('foo'));
    }

    public function testSetProperty()
    {
        $frame = new Frame;
        $this->assertFalse($frame->hasProperty('foo'));
        $test = $frame->setProperty('foo', 'bar');
        $this->assertEquals('bar', $frame->foo);
        $this->assertTrue($frame->hasProperty('foo'));
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
}
