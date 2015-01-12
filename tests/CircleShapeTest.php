<?php

use Intervention\Image\Gd\Shapes\CircleShape as CircleGd;
use Intervention\Image\Imagick\Shapes\CircleShape as CircleImagick;

class CircleShapeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGdConstructor()
    {
        $circle = new CircleGd(250);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\CircleShape', $circle);
        $this->assertEquals(250, $circle->diameter);
        
    }

    public function testGdApplyToImage()
    {
        $core = imagecreatetruecolor(300, 200);
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $circle = new CircleGd(250);
        $result = $circle->applyToImage($image, 10, 20);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\CircleShape', $circle);
        $this->assertTrue($result);
    }

    public function testImagickConstructor()
    {
        $circle = new CircleImagick(250);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\CircleShape', $circle);
        $this->assertEquals(250, $circle->width);
    }

    public function testImagickApplyToImage()
    {
        $core = Mockery::mock('\Imagick');
        $core->shouldReceive('drawimage')->once();
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $circle = new CircleImagick(250);
        $result = $circle->applyToImage($image, 10, 20);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\CircleShape', $circle);
        $this->assertTrue($result);
    }

}
