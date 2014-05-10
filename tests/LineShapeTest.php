<?php

use Intervention\Image\Gd\Shapes\LineShape as LineGd;
use Intervention\Image\Imagick\Shapes\LineShape as LineImagick;

class LineShapeTest extends PHPUnit_Framework_TestCase
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
        $core = imagecreatetruecolor(300, 200);
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $line = new LineGd(10, 15);
        $result = $line->applyToImage($image, 100, 200);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\LineShape', $line);
        $this->assertTrue($result);

        // imagick
        $core = Mockery::mock('\Imagick');
        $core->shouldReceive('drawimage')->once();
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $line = new LineImagick(10, 15);
        $result = $line->applyToImage($image, 100, 200);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\LineShape', $line);
        $this->assertTrue($result);
    }
}
