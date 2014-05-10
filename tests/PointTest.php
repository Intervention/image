<?php

use Intervention\Image\Point;

class PointTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $point = new Point;
        $this->assertInstanceOf('Intervention\Image\Point', $point);
        $this->assertEquals(0, $point->x);
        $this->assertEquals(0, $point->y);
    }

    public function testConstructorWithParameters()
    {
        $point = new Point(40, 50);
        $this->assertInstanceOf('Intervention\Image\Point', $point);
        $this->assertEquals(40, $point->x);
        $this->assertEquals(50, $point->y);
    }

    public function testSetX()
    {
        $point = new Point(0, 0);
        $point->setX(100);
        $this->assertEquals(100, $point->x);
        $this->assertEquals(0, $point->y);
    }

    public function testSetY()
    {
        $point = new Point(0, 0);
        $point->setY(100);
        $this->assertEquals(0, $point->x);
        $this->assertEquals(100, $point->y);
    }

    public function testSetPosition()
    {
        $point = new Point(0, 0);
        $point->setPosition(100, 200);
        $this->assertEquals(100, $point->x);
        $this->assertEquals(200, $point->y);
    }
}
