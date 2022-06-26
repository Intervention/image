<?php

namespace Intervention\Image\Tests\Geometry;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Geometry\Point
 */
class PointTest extends TestCase
{
    public function testConstructor()
    {
        $point = new Point();
        $this->assertInstanceOf(Point::class, $point);
        $this->assertEquals(0, $point->getX());
        $this->assertEquals(0, $point->getY());
    }

    public function testConstructorWithParameters()
    {
        $point = new Point(40, 50);
        $this->assertInstanceOf(Point::class, $point);
        $this->assertEquals(40, $point->getX());
        $this->assertEquals(50, $point->getY());
    }

    public function testGetSetX()
    {
        $point = new Point(0, 0);
        $point->setX(100);
        $this->assertEquals(100, $point->getX());
        $this->assertEquals(0, $point->getY());
    }

    public function testGetSetY()
    {
        $point = new Point(0, 0);
        $point->setY(100);
        $this->assertEquals(0, $point->getX());
        $this->assertEquals(100, $point->getY());
    }

    public function testmoveX()
    {
        $point = new Point(50, 50);
        $point->moveX(100);
        $this->assertEquals(150, $point->getX());
        $this->assertEquals(50, $point->getY());
    }

    public function testmoveY()
    {
        $point = new Point(50, 50);
        $point->moveY(100);
        $this->assertEquals(50, $point->getX());
        $this->assertEquals(150, $point->getY());
    }

    public function testSetPosition()
    {
        $point = new Point(0, 0);
        $point->setPosition(100, 200);
        $this->assertEquals(100, $point->getX());
        $this->assertEquals(200, $point->getY());
    }

    public function testRotate()
    {
        $point = new Point(30, 0);
        $point->rotate(90, new Point(0, 0));
        $this->assertEquals(0, $point->getX());
        $this->assertEquals(30, $point->getY());

        $point->rotate(90, new Point(0, 0));
        $this->assertEquals(-30, $point->getX());
        $this->assertEquals(0, $point->getY());

        $point = new Point(300, 200);
        $point->rotate(90, new Point(0, 0));
        $this->assertEquals(-200, $point->getX());
        $this->assertEquals(300, $point->getY());

        $point = new Point(0, 74);
        $point->rotate(45, new Point(0, 0));
        $this->assertEquals(-52, $point->getX());
        $this->assertEquals(52, $point->getY());
    }
}
