<?php

use Intervention\Image\Gd\Gif\Encoder as Encoder;

class GifEncoderTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testSetCanvas()
    {
        $encoder = new Encoder;
        $result = $encoder->setCanvas(300, 200);
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Encoder', $result);
        $this->assertEquals(300, $encoder->canvasWidth);
        $this->assertEquals(200, $encoder->canvasHeight);
    }

    public function testSetLoops()
    {
        $encoder = new Encoder;
        $result = $encoder->setLoops(6);
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Encoder', $result);
        $this->assertEquals(6, $encoder->loops);
    }

    public function testSetGlobalColorTable()
    {
        $encoder = new Encoder;
        $result = $encoder->setGlobalColorTable('foo');
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Encoder', $result);
        $this->assertEquals('foo', $encoder->globalColorTable);
    }

    public function testSetFrames()
    {
        $encoder = new Encoder;
        $frames = array('foo', 'bar', 'baz');
        $encoder->setFrames($frames);
        $this->assertEquals($frames, $encoder->frames);
    }

    public function testSetBackgroundColorIndex()
    {
        $encoder = new Encoder;
        $result = $encoder->setBackgroundColorIndex('foo');
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Encoder', $result);
        $this->assertEquals('foo', $encoder->backgroundColorIndex);
    }

    public function testSetFromDecoded()
    {
        $encoder = new Encoder;
        $decoded = Mockery::mock('Intervention\Image\Gd\Gif\Decoded');
        $decoded->shouldReceive('getCanvasWidth')->andReturn(300);
        $decoded->shouldReceive('getCanvasHeight')->andReturn(200);
        $decoded->shouldReceive('getGlobalColorTable')->andReturn('global_color_table');
        $decoded->shouldReceive('getBackgroundColorIndex')->andReturn('background_color_index');
        $decoded->shouldReceive('getLoops')->andReturn(2);
        $decoded->shouldReceive('getFrames')->andReturn(array('frame1', 'frame2', 'frame3'));
        $encoder->setFromDecoded($decoded);
        $this->assertEquals(300, $encoder->canvasWidth);
        $this->assertEquals(200, $encoder->canvasHeight);
        $this->assertEquals('global_color_table', $encoder->globalColorTable);
        $this->assertEquals('background_color_index', $encoder->backgroundColorIndex);
        $this->assertEquals(2, $encoder->loops);
        $this->assertEquals(3, count($encoder->frames));
        $this->assertTrue($encoder->doesLoop());
    }

    public function testAddFrame()
    {
        $encoder = new Encoder;
        $encoder->addFrame(Mockery::mock('Intervention\Image\Gd\Gif\Frame'));
        $encoder->addFrame(Mockery::mock('Intervention\Image\Gd\Gif\Frame'));
        $this->assertEquals(2, count($encoder->frames));
    }

    public function testCreateFrameFromGdResource()
    {
        $encoder = new Encoder;
        $resource = imagecreate(10, 10);
        $encoder->createFrameFromGdResource($resource, 10);
        $this->assertEquals(1, count($encoder->frames));
    }

    public function testIsAnimated()
    {
        $encoder = new Encoder;
        $this->assertFalse($encoder->isAnimated());
        $encoder->addFrame(Mockery::mock('Intervention\Image\Gd\Gif\Frame'));
        $this->assertFalse($encoder->isAnimated());
        $encoder->addFrame(Mockery::mock('Intervention\Image\Gd\Gif\Frame'));
        $this->assertTrue($encoder->isAnimated());
    }

    public function testDoesLoop()
    {
        $encoder = new Encoder;
        $this->assertFalse($encoder->doesLoop());
        $encoder->setLoops(10);
        $this->assertTrue($encoder->doesLoop());
    }
}
