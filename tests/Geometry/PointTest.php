<?php

declare(strict_types=1);

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
        $this->assertEquals(0, $point->x());
        $this->assertEquals(0, $point->y());
    }

    public function testConstructorWithParameters()
    {
        $point = new Point(40, 50);
        $this->assertInstanceOf(Point::class, $point);
        $this->assertEquals(40, $point->x());
        $this->assertEquals(50, $point->y());
    }

    public function testGetSetX()
    {
        $point = new Point(0, 0);
        $point->setX(100);
        $this->assertEquals(100, $point->x());
        $this->assertEquals(0, $point->y());
    }

    public function testGetSetY()
    {
        $point = new Point(0, 0);
        $point->setY(100);
        $this->assertEquals(0, $point->x());
        $this->assertEquals(100, $point->y());
    }

    public function testmoveX()
    {
        $point = new Point(50, 50);
        $point->moveX(100);
        $this->assertEquals(150, $point->x());
        $this->assertEquals(50, $point->y());
    }

    public function testmoveY()
    {
        $point = new Point(50, 50);
        $point->moveY(100);
        $this->assertEquals(50, $point->x());
        $this->assertEquals(150, $point->y());
    }

    public function testSetPosition()
    {
        $point = new Point(0, 0);
        $point->setPosition(100, 200);
        $this->assertEquals(100, $point->x());
        $this->assertEquals(200, $point->y());
    }

    public function testRotate()
    {
        $point = new Point(30, 0);
        $point->rotate(90, new Point(0, 0));
        $this->assertEquals(0, $point->x());
        $this->assertEquals(30, $point->y());

        $point->rotate(90, new Point(0, 0));
        $this->assertEquals(-30, $point->x());
        $this->assertEquals(0, $point->y());

        $point = new Point(300, 200);
        $point->rotate(90, new Point(0, 0));
        $this->assertEquals(-200, $point->x());
        $this->assertEquals(300, $point->y());

        $point = new Point(0, 74);
        $point->rotate(45, new Point(0, 0));
        $this->assertEquals(-52, $point->x());
        $this->assertEquals(52, $point->y());
    }
}
