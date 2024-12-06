<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Bezier::class)]
final class BezierTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $bezier = new Bezier([]);
        $this->assertInstanceOf(Bezier::class, $bezier);
        $this->assertEquals(0, $bezier->count());
    }

    public function testCount(): void
    {
        $bezier = new Bezier([
            new Point(),
            new Point(),
            new Point(),
            new Point()
        ]);
        $this->assertEquals(4, $bezier->count());
    }

    public function testArrayAccess(): void
    {
        $bezier = new Bezier([
            new Point(),
            new Point(),
            new Point(),
            new Point()
        ]);
        $this->assertInstanceOf(Point::class, $bezier[0]);
        $this->assertInstanceOf(Point::class, $bezier[1]);
        $this->assertInstanceOf(Point::class, $bezier[2]);
        $this->assertInstanceOf(Point::class, $bezier[3]);
    }

    public function testAddPoint(): void
    {
        $bezier = new Bezier([
            new Point(),
            new Point()
        ]);
        $this->assertEquals(2, $bezier->count());
        $result = $bezier->addPoint(new Point());
        $this->assertEquals(3, $bezier->count());
        $this->assertInstanceOf(Bezier::class, $result);
    }

    public function testFirst(): void
    {
        $bezier = new Bezier([
            new Point(50, 45),
            new Point(100, -49),
            new Point(-100, 100),
            new Point(200, 300),
        ]);
        $this->assertEquals(50, $bezier->first()->x());
        $this->assertEquals(45, $bezier->first()->y());
    }

    public function testFirstEmpty(): void
    {
        $bezier = new Bezier();
        $this->assertNull($bezier->first());
    }

    public function testSecond(): void
    {
        $bezier = new Bezier([
            new Point(50, 45),
            new Point(100, -49),
            new Point(-100, 100),
            new Point(200, 300),
        ]);
        $this->assertEquals(100, $bezier->second()->x());
        $this->assertEquals(-49, $bezier->second()->y());
    }

    public function testSecondEmpty(): void
    {
        $bezier = new Bezier();
        $this->assertNull($bezier->second());
    }

    public function testThird(): void
    {
        $bezier = new Bezier([
            new Point(50, 45),
            new Point(100, -49),
            new Point(-100, 100),
            new Point(200, 300),
        ]);
        $this->assertEquals(-100, $bezier->third()->x());
        $this->assertEquals(100, $bezier->third()->y());
    }

    public function testThirdEmpty(): void
    {
        $bezier = new Bezier();
        $this->assertNull($bezier->third());
    }

    public function testLast(): void
    {
        $bezier = new Bezier([
            new Point(50, 45),
            new Point(100, -49),
            new Point(-100, 100),
            new Point(200, 300),
        ]);
        $this->assertEquals(200, $bezier->last()->x());
        $this->assertEquals(300, $bezier->last()->y());
    }

    public function testLastEmpty(): void
    {
        $bezier = new Bezier();
        $this->assertNull($bezier->last());
    }

    public function testOffsetExists(): void
    {
        $bezier = new Bezier();
        $this->assertFalse($bezier->offsetExists(0));
        $this->assertFalse($bezier->offsetExists(1));
        $bezier->addPoint(new Point(0, 0));
        $this->assertTrue($bezier->offsetExists(0));
        $this->assertFalse($bezier->offsetExists(1));
    }

    public function testOffsetSetUnset(): void
    {
        $bezier = new Bezier();
        $bezier->offsetSet(0, new Point());
        $bezier->offsetSet(2, new Point());
        $this->assertTrue($bezier->offsetExists(0));
        $this->assertFalse($bezier->offsetExists(1));
        $this->assertTrue($bezier->offsetExists(2));
        $bezier->offsetUnset(2);
        $this->assertTrue($bezier->offsetExists(0));
        $this->assertFalse($bezier->offsetExists(1));
        $this->assertFalse($bezier->offsetExists(2));
    }

    public function testGetSetPivotPoint(): void
    {
        $bezier = new Bezier();
        $this->assertInstanceOf(Point::class, $bezier->pivot());
        $this->assertEquals(0, $bezier->pivot()->x());
        $this->assertEquals(0, $bezier->pivot()->y());
        $result = $bezier->setPivot(new Point(12, 34));
        $this->assertInstanceOf(Bezier::class, $result);
        $this->assertEquals(12, $bezier->pivot()->x());
        $this->assertEquals(34, $bezier->pivot()->y());
    }

    public function testToArray(): void
    {
        $bezier = new Bezier([
            new Point(50, 50),
            new Point(100, 50),
            new Point(-50, -100),
            new Point(50, 100),
        ]);
        $this->assertEquals([50, 50, 100, 50, -50, -100, 50, 100], $bezier->toArray());
    }
}
