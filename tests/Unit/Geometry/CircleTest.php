<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Circle::class)]
final class CircleTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $circle = new Circle(100, new Point(1, 2));
        $this->assertInstanceOf(Circle::class, $circle);
        $this->assertEquals(100, $circle->diameter());
        $this->assertInstanceOf(Point::class, $circle->pivot());
    }

    public function testSetGetDiameter(): void
    {
        $circle = new Circle(100, new Point(1, 2));
        $this->assertEquals(100, $circle->diameter());
        $result = $circle->setDiameter(200);
        $this->assertInstanceOf(Circle::class, $result);
        $this->assertEquals(200, $result->diameter());
        $this->assertEquals(200, $circle->diameter());
    }

    public function testSetGetRadius(): void
    {
        $circle = new Circle(100, new Point(1, 2));
        $this->assertEquals(50, $circle->radius());
        $result = $circle->setRadius(200);
        $this->assertInstanceOf(Circle::class, $result);
        $this->assertEquals(400, $result->diameter());
        $this->assertEquals(400, $circle->diameter());
        $this->assertEquals(200, $result->radius());
        $this->assertEquals(200, $circle->radius());
    }
}
