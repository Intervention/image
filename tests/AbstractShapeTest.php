<?php

class AbstractShapeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testBackground()
    {
        $shape = $this->getMockForAbstractClass('\Intervention\Image\AbstractShape');
        $shape->background('foo');
        $this->assertEquals('foo', $shape->background);
        $this->assertEquals(0, $shape->border_width);
    }

    public function testBorder()
    {
        $shape = $this->getMockForAbstractClass('\Intervention\Image\AbstractShape');
        $shape->border(4);
        $this->assertEquals(4, $shape->border_width);
        $this->assertEquals('#000000', $shape->border_color);
    }

    public function testBorderWithColor()
    {
        $shape = $this->getMockForAbstractClass('\Intervention\Image\AbstractShape');
        $shape->border(3, '#ff00ff');
        $this->assertEquals(3, $shape->border_width);
        $this->assertEquals('#ff00ff', $shape->border_color);
    }

    public function testHasBorder()
    {
        $shape = $this->getMockForAbstractClass('\Intervention\Image\AbstractShape');
        $this->assertFalse($shape->hasBorder());
        $shape->border(1);
        $this->assertTrue($shape->hasBorder());
    }
}
