<?php

use Intervention\Image\Gd\Shapes\PolygonShape as PolygonGd;
use Intervention\Image\Imagick\Shapes\PolygonShape as PolygonImagick;

class PolygonShapeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGdConstructor()
    {
        $polygon = new PolygonGd(array(1, 2, 3, 4, 5, 6));
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\PolygonShape', $polygon);
        $this->assertEquals(array(1, 2, 3, 4, 5, 6), $polygon->points);
        
    }

    public function testGdApplyToImage()
    {
        $core = imagecreatetruecolor(300, 200);
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $polygon = new PolygonGd(array(1, 2, 3, 4, 5, 6));
        $result = $polygon->applyToImage($image);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\PolygonShape', $polygon);
        $this->assertTrue($result);
    }

    public function testImagickConstructor()
    {
        $polygon = new PolygonImagick(array(1, 2, 3, 4, 5, 6));
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\PolygonShape', $polygon);
        $this->assertEquals(array(
            array('x' => 1, 'y' => 2), 
            array('x' => 3, 'y' => 4), 
            array('x' => 5, 'y' => 6)), 
        $polygon->points);
        
    }

    public function testImagickApplyToImage()
    {
        $core = Mockery::mock('\Imagick');
        $core->shouldReceive('drawimage')->once();
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $polygon = new PolygonImagick(array(1, 2, 3, 4, 5, 6));
        $result = $polygon->applyToImage($image);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\PolygonShape', $polygon);
        $this->assertTrue($result);
    }

}
