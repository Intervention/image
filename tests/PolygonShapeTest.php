<?php

use Intervention\Image\Gd\Shapes\PolygonShape as PolygonGd;
use Intervention\Image\Imagick\Shapes\PolygonShape as PolygonImagick;

class PolygonShapeTest extends CommandTestCase
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
        $image = $this->getTestImage('gd');
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
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('drawimage')->times(3);
        $polygon = new PolygonImagick(array(1, 2, 3, 4, 5, 6));
        $result = $polygon->applyToImage($image);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\PolygonShape', $polygon);
        $this->assertTrue($result);
    }

}
