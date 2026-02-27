<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Factories\BezierFactory;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

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

    public function testPosition(): void
    {
        $bezier = new Bezier([], new Point(10, 20));
        $this->assertEquals(10, $bezier->position()->x());
        $this->assertEquals(20, $bezier->position()->y());
    }

    public function testSetPosition(): void
    {
        $bezier = new Bezier([]);
        $result = $bezier->setPosition(new Point(50, 60));
        $this->assertInstanceOf(Bezier::class, $result);
        $this->assertEquals(50, $bezier->pivot()->x());
        $this->assertEquals(60, $bezier->pivot()->y());
    }

    public function testGetIterator(): void
    {
        $bezier = new Bezier([
            new Point(1, 2),
            new Point(3, 4),
        ]);
        $points = iterator_to_array($bezier->getIterator());
        $this->assertCount(2, $points);
        $this->assertEquals(1, $points[0]->x());
        $this->assertEquals(2, $points[0]->y());
        $this->assertEquals(3, $points[1]->x());
        $this->assertEquals(4, $points[1]->y());
    }

    public function testFactory(): void
    {
        $bezier = new Bezier([new Point(0, 0), new Point(10, 10)]);
        $factory = $bezier->factory();
        $this->assertInstanceOf(BezierFactory::class, $factory);
    }

    public function testClone(): void
    {
        $bezier = new Bezier([
            new Point(1, 2),
            new Point(3, 4),
        ], new Point(10, 20));
        $clone = clone $bezier;

        // verify deep copy of points
        $clone->first()->setX(99);
        $this->assertEquals(1, $bezier->first()->x());
        $this->assertEquals(99, $clone->first()->x());

        // verify deep copy of pivot
        $clone->pivot()->setX(88);
        $this->assertEquals(10, $bezier->pivot()->x());
        $this->assertEquals(88, $clone->pivot()->x());
    }

    public function testCloneWithColors(): void
    {
        $bezier = new Bezier([new Point(0, 0)]);
        $bezier->setBackgroundColor(new RgbColor(255, 0, 0));
        $bezier->setBorderColor(new RgbColor(0, 0, 255));

        $clone = clone $bezier;

        $this->assertNotSame($bezier->backgroundColor(), $clone->backgroundColor());
        $this->assertNotSame($bezier->borderColor(), $clone->borderColor());
    }
}
