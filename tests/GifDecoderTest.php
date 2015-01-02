<?php

use Intervention\Image\Gd\Gif\Decoder as Decoder;

class GifDecoderTest extends PHPUnit_Framework_TestCase
{
    public $decoder;

    public function setUp()
    {
        $this->decoder = $this->getTestDecoder('tests/images/animation.gif');
    }

    public function tearDown()
    {
        # code...
    }

    private function getTestDecoder($file)
    {
        return new Decoder($file);
    }

    public function testConstructorFromFile()
    {
        $decoder = new Decoder('tests/images/animation.gif');
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoder', $decoder);
    }

    public function testInitFromData()
    {
        $data = file_get_contents('tests/images/animation.gif');

        $decoder = new Decoder;
        $decoder->initFromData($data);
        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoder', $decoder);
    }

    public function testDecode()
    {
        $decoded = $this->decoder->decode();

        $this->assertInstanceOf('Intervention\Image\Gd\Gif\Decoded', $decoded);
        $this->assertEquals(8, $decoded->countFrames());
        $this->assertTrue($decoded->hasGlobalColorTable());
        $this->assertEquals(32, $decoded->countGlobalColors());
        $this->assertEquals(2, $decoded->getLoops());

        $offsets = array(
            array('left' => 0, 'top' => 0),
            array('left' => 5, 'top' => 2),
            array('left' => 1, 'top' => 0),
            array('left' => 0, 'top' => 0),
            array('left' => 8, 'top' => 5),
            array('left' => 5, 'top' => 2),
            array('left' => 1, 'top' => 0),
            array('left' => 0, 'top' => 0)
        );

        $sizes = array(
            array('width' => 20, 'height' => 15),
            array('width' => 10, 'height' => 10),
            array('width' => 17, 'height' => 15),
            array('width' => 20, 'height' => 15),
            array('width' => 5, 'height' => 5),
            array('width' => 10, 'height' => 10),
            array('width' => 17, 'height' => 15),
            array('width' => 20, 'height' => 15)
        );

        $delays = array(20, 20, 20, 20, 20, 20, 20, 20);
        $interlaced = array(true, false, false, false, false, false, false, false);
        $localColorTables = array(null, null, null, null, null, null, null, null);

        foreach ($decoded->getFrames() as $key => $frame) {
            $this->assertEquals($sizes[$key]['width'], $frame->size->width);
            $this->assertEquals($sizes[$key]['height'], $frame->size->height);
            $this->assertEquals($offsets[$key]['left'], $frame->offset->left);
            $this->assertEquals($offsets[$key]['top'], $frame->offset->top);
            $this->assertEquals($delays[$key], $frame->decodeDelay());
            $this->assertEquals($interlaced[$key], $frame->isInterlaced());
            $this->assertEquals($localColorTables[$key], $frame->getLocalColorTable());
            $this->assertFalse($frame->hasLocalColorTable());
        }
    }
}
