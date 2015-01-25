<?php

use Intervention\Image\Gd\Shapes\RectangleShape as RectangleGd;
use Intervention\Image\Imagick\Shapes\RectangleShape as RectangleImagick;

class RectangleShapeTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testConstructor()
    {
        // gd 
        $rectangle = new RectangleGd(10, 15, 100, 150);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\RectangleShape', $rectangle);
        $this->assertEquals(10, $rectangle->x1);
        $this->assertEquals(15, $rectangle->y1);
        $this->assertEquals(100, $rectangle->x2);
        $this->assertEquals(150, $rectangle->y2);

        // imagick
        $rectangle = new RectangleImagick(10, 15, 100, 150);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\RectangleShape', $rectangle);
        $this->assertEquals(10, $rectangle->x1);
        $this->assertEquals(15, $rectangle->y1);
        $this->assertEquals(100, $rectangle->x2);
        $this->assertEquals(150, $rectangle->y2);
    }

    public function testApplyToImage()
    {
        // gd
        $image = $this->getTestImage('gd');
        $rectangle = new RectangleGd(10, 15, 100, 150);
        $result = $rectangle->applyToImage($image, 10, 20);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\RectangleShape', $rectangle);
        $this->assertTrue($result);

        // imagick
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('drawimage')->times(3);
        $rectangle = new RectangleImagick(10, 15, 100, 150);
        $result = $rectangle->applyToImage($image, 10, 20);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\RectangleShape', $rectangle);
        $this->assertTrue($result);
    }
}
