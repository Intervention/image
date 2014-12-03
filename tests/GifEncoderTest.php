<?php

use Intervention\Image\Tools\Gif\Encoder as Encoder;

class GifEncoderTest extends PHPUnit_Framework_TestCase
{
    public function testSetCanvas()
    {
        $encoder = new Encoder;
        $result = $encoder->setCanvas(300, 200);
        $this->assertInstanceOf('Intervention\Image\Tools\Gif\Encoder', $result);
        $this->assertEquals(300, $encoder->canvasWidth);
        $this->assertEquals(200, $encoder->canvasHeight);
    }

    public function testSetLoops()
    {
        $encoder = new Encoder;
        $result = $encoder->setLoops(6);
        $this->assertInstanceOf('Intervention\Image\Tools\Gif\Encoder', $result);
        $this->assertEquals(6, $encoder->loops);
    }

    public function testSetGlobalColorTable()
    {
        $encoder = new Encoder;
        $result = $encoder->setGlobalColorTable('foo');
        $this->assertInstanceOf('Intervention\Image\Tools\Gif\Encoder', $result);
        $this->assertEquals('foo', $encoder->globalColorTable);
    }
}
