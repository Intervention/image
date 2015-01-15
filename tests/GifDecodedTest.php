<?php

use Intervention\Image\Gd\Gif\Decoded;

class GifDecodedTest extends PHPUnit_Framework_TestCase
{
    public function testSetGetHeader()
    {
        $decoded = new Decoded;
        $obj = $decoded->setHeader('foo');
        $this->assertEquals('foo', $decoded->getHeader());
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoded', $obj);
    }

    public function testSetGetLogicalScreenDescriptor()
    {
        $decoded = new Decoded;
        $obj = $decoded->setLogicalScreenDescriptor('foo');
        $this->assertEquals('foo', $decoded->getLogicalScreenDescriptor());
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoded', $obj);
    }

    public function testSetGetGlobalColorTable()
    {
        $decoded = new Decoded;
        $obj = $decoded->setGlobalColorTable('foo');
        $this->assertEquals('foo', $decoded->getGlobalColorTable());
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoded', $obj);
    }

    public function testSetGetNetscapeExtension()
    {
        $decoded = new Decoded;
        $obj = $decoded->setNetscapeExtension('foo');
        $this->assertEquals('foo', $decoded->getNetscapeExtension());
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoded', $obj);
    }

    public function testSetGetPlaintextExtension()
    {
        $decoded = new Decoded;
        $obj = $decoded->setPlaintextExtension('foo');
        $this->assertEquals('foo', $decoded->getPlaintextExtension());
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoded', $obj);
    }

    public function testSetGetCommentExtension()
    {
        $decoded = new Decoded;
        $obj = $decoded->setCommentExtension('foo');
        $this->assertEquals('foo', $decoded->getCommentExtension());
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoded', $obj);
    }

    public function testGetCanvasWidth()
    {
        $decoded = new Decoded;
        $this->assertEquals(false, $decoded->getCanvasWidth());

        $decoded = new Decoded;
        $decoded->setLogicalScreenDescriptor("\x0A\x00\x0A\x00\x00\x00\x00");
        $this->assertEquals(10, $decoded->getCanvasWidth());
    }

    public function testGetCanvasHeight()
    {
        $decoded = new Decoded;
        $this->assertEquals(false, $decoded->getCanvasHeight());

        $decoded = new Decoded;
        $decoded->setLogicalScreenDescriptor("\x0A\x00\x0A\x00\x00\x00\x00");
        $this->assertEquals(10, $decoded->getCanvasHeight());
    }

    public function testGetLoops()
    {
        $decoded = new Decoded;
        $this->assertEquals(null, $decoded->getLoops());

        $decoded = new Decoded;
        $decoded->setNetscapeExtension("\x0B\x4E\x45\x54\x53\x43\x41\x50\x45\x32\x2E\x30\x03\x01\x05\x00\x00");
        $this->assertEquals(5, $decoded->getLoops());
    }

    public function testHasGlobalColorTable()
    {
        $decoded = new Decoded;
        $this->assertFalse($decoded->hasGlobalColorTable());

        $decoded = new Decoded;
        $decoded->setLogicalScreenDescriptor("\x0A\x00\x0A\x00\x00\x00\x00");
        $this->assertFalse($decoded->hasGlobalColorTable());

        $decoded = new Decoded;
        $decoded->setLogicalScreenDescriptor("\x0A\x00\x0A\x00\x91\x00\x00");
        $this->assertTrue($decoded->hasGlobalColorTable());
    }

    public function testCountGlobalColors()
    {
        $decoded = new Decoded;
        $this->assertEquals(0, $decoded->countGlobalColors());

        $decoded = new Decoded;
        $decoded->setLogicalScreenDescriptor("\x0A\x00\x0A\x00\x91\x00\x00");
        $this->assertEquals(4, $decoded->countGlobalColors());

        $decoded = new Decoded;
        $decoded->setLogicalScreenDescriptor("\x0A\x00\x0A\x00\x91\x00\x00");
        $this->assertEquals(4, $decoded->countGlobalColors());
    }

    public function testGetBackgroundColorIndex()
    {
        $decoded = new Decoded;
        $this->assertEquals(0, $decoded->getBackgroundColorIndex());

        $decoded = new Decoded;
        $decoded->setLogicalScreenDescriptor("\x0A\x00\x0A\x00\x00\x05\x00");
        $this->assertEquals(5, $decoded->getBackgroundColorIndex());
    }

    public function testGetFrame()
    {
        $decoded = new Decoded;
        $decoded->addImageData('foo');
        $decoded->addImageData('bar');
        $frame = $decoded->getFrame();
        $this->assertInstanceOf('\Intervention\Image\Gd\Gif\Frame', $frame);
        $this->assertEquals('foo', $frame->imageData);
        $frame = $decoded->getFrame(1);
        $this->assertInstanceOf('\Intervention\Image\Gd\Gif\Frame', $frame);
        $this->assertEquals('bar', $frame->imageData);
    }

    /**
     * @expectedException \Intervention\Image\Exception\RuntimeException
     */
    public function testGetFrameNotExisting()
    {
        $decoded = new Decoded;
        $frame = $decoded->getFrame();
    }
}
