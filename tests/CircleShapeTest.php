<?php

use Intervention\Image\Gd\Shapes\CircleShape as CircleGd;
use Intervention\Image\Imagick\Shapes\CircleShape as CircleImagick;

class CircleShapeTest extends CommandTestCase
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
        $image = $this->getTestImage('gd');
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
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('drawimage')->times(3);
        $circle = new CircleImagick(250);
        $result = $circle->applyToImage($image, 10, 20);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\CircleShape', $circle);
        $this->assertTrue($result);
    }

}
