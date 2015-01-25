<?php

use Intervention\Image\Gd\Shapes\EllipseShape as EllipseGd;
use Intervention\Image\Imagick\Shapes\EllipseShape as EllipseImagick;

class EllipseShapeTest extends CommandTestCase
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
        $image = $this->getTestImage('gd');
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
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('drawimage')->times(3);
        $ellipse = new EllipseImagick(250, 150);
        $result = $ellipse->applyToImage($image, 10, 20);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\EllipseShape', $ellipse);
        $this->assertTrue($result);
    }

}
