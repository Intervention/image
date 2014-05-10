<?php

use Intervention\Image\Gd\Shapes\EllipseShape as EllipseGd;
use Intervention\Image\Imagick\Shapes\EllipseShape as EllipseImagick;

class EllipseShapeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGdConstructor()
    {
        $ellipse = new EllipseGd(250, 150);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\EllipseShape', $ellipse);
        $this->assertEquals(250, $ellipse->width);
        $this->assertEquals(150, $ellipse->height);
        
    }

    public function testGdApplyToImage()
    {
        $core = imagecreatetruecolor(300, 200);
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $ellipse = new EllipseGd(250, 150);
        $result = $ellipse->applyToImage($image, 10, 20);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\EllipseShape', $ellipse);
        $this->assertTrue($result);
    }

    public function testImagickConstructor()
    {
        $ellipse = new EllipseImagick(250, 150);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\EllipseShape', $ellipse);
        $this->assertEquals(250, $ellipse->width);
        $this->assertEquals(150, $ellipse->height);
        
    }

    public function testImagickApplyToImage()
    {
        $core = Mockery::mock('\Imagick');
        $core->shouldReceive('drawimage')->once();
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $ellipse = new EllipseImagick(250, 150);
        $result = $ellipse->applyToImage($image, 10, 20);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\EllipseShape', $ellipse);
        $this->assertTrue($result);
    }

}
