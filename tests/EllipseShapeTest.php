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

    public function testImagickConstructor()
    {
        $ellipse = new EllipseImagick(250, 150);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\EllipseShape', $ellipse);
        $this->assertEquals(250, $ellipse->width);
        $this->assertEquals(150, $ellipse->height);
        
    }
}
