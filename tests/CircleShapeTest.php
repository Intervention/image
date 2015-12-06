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

    public function testImagickConstructor()
    {
        $circle = new CircleImagick(250);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\CircleShape', $circle);
        $this->assertEquals(250, $circle->width);
    }
}
