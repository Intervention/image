<?php

use Intervention\Image\Gd\Shapes\LineShape as LineGd;
use Intervention\Image\Imagick\Shapes\LineShape as LineImagick;

class LineShapeTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testConstructor()
    {
        // gd 
        $line = new LineGd(10, 15);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\LineShape', $line);
        $this->assertEquals(10, $line->x);
        $this->assertEquals(15, $line->y);

        // imagick
        $line = new LineImagick(10, 15);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\LineShape', $line);
        $this->assertEquals(10, $line->x);
        $this->assertEquals(15, $line->y);
    }

    public function testApplyToImage()
    {
        // gd
        $image = $this->getTestImage('gd');
        $line = new LineGd(10, 15);
        $result = $line->applyToImage($image, 100, 200);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\LineShape', $line);
        $this->assertTrue($result);

        // imagick
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('drawimage')->times(3);
        $line = new LineImagick(10, 15);
        $result = $line->applyToImage($image, 100, 200);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\LineShape', $line);
        $this->assertTrue($result);
    }
}
