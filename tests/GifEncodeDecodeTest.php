<?php

use Intervention\Image\Gd\Gif\Encoder as Encoder;
use Intervention\Image\Gd\Gif\Decoder as Decoder;

class GifEncodeDecodeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testEncodeDecoded()
    {
        // create decoded
        $decoder = new Decoder('tests/images/animation.gif');
        $decoded = $decoder->decode();

        // check before encoding
        $this->assertEquals(20, $decoded->getCanvasWidth());
        $this->assertEquals(15, $decoded->getCanvasHeight());
        $this->assertEquals(8, $decoded->countFrames());
        $this->assertEquals(2, $decoded->getLoops());
        $this->assertEquals(32, $decoded->countGlobalColors());
        $this->assertTrue($decoded->hasGlobalColorTable());

        foreach ($decoded->getFrames() as $frame) {
            $this->assertEquals(20, $frame->getDelay());
        }
    
        // encode Decoded        
        $encoder = new Encoder;
        $encoder->setFromDecoded($decoded);
        $encoded = $encoder->encode();
        $decoder->initFromData($encoded);
        $decoded = $decoder->decode();

        // check after encoding
        $this->assertEquals(20, $decoded->getCanvasWidth());
        $this->assertEquals(15, $decoded->getCanvasHeight());
        $this->assertEquals(8, $decoded->countFrames());
        $this->assertEquals(2, $decoded->getLoops());
        $this->assertEquals(32, $decoded->countGlobalColors());
        $this->assertTrue($decoded->hasGlobalColorTable());

        foreach ($decoded->getFrames() as $frame) {
            $this->assertEquals(20, $frame->getDelay());
        }
    }

    public function testDecodeEncoded()
    {
        // create two resource
        $res1 = imagecreatetruecolor(20, 15);
        imagefill($res1, 0, 0, 850736919);
        $res2 = imagecreatetruecolor(20, 15);
        imagefill($res1, 0, 0, 11876119);

        // create encoded
        $encoder = new Encoder;
        $encoder->setCanvas(20, 15);
        $encoder->setLoops(2);
        $encoder->createFrameFromGdResource($res1, 100);
        $encoder->createFrameFromGdResource($res2, 100);
        $encoded = $encoder->encode();

        // decode encoded
        $decoder = new Decoder;
        $decoder->initFromData($encoded);
        $decoded = $decoder->decode();

        // check after decoding
        $this->assertEquals(20, $decoded->getCanvasWidth());
        $this->assertEquals(15, $decoded->getCanvasHeight());
        $this->assertEquals(2, $decoded->countFrames());
        $this->assertEquals(2, $decoded->getLoops());
        $this->assertEquals(32, $decoded->countGlobalColors());
        $this->assertFalse($decoded->hasGlobalColorTable());

        foreach ($decoded->getFrames() as $frame) {
            $this->assertEquals(100, $frame->getDelay());
        }
    }
}
