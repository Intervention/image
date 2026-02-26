<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Geometry\Factories\LineFactory;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Line::class)]
final class LineTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertInstanceOf(Line::class, $line);
    }

    public function testPosition(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertEquals(1, $line->position()->x());
        $this->assertEquals(2, $line->position()->y());
    }

    public function testSetGetStart(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertEquals(1, $line->start()->x());
        $this->assertEquals(2, $line->start()->y());
        $result = $line->setStart(new Point(10, 20));
        $this->assertInstanceOf(Line::class, $result);
        $this->assertEquals(10, $line->start()->x());
        $this->assertEquals(20, $line->start()->y());
    }

    public function testSetGetEnd(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertEquals(3, $line->end()->x());
        $this->assertEquals(4, $line->end()->y());
        $result = $line->setEnd(new Point(30, 40));
        $this->assertInstanceOf(Line::class, $result);
        $this->assertEquals(30, $line->end()->x());
        $this->assertEquals(40, $line->end()->y());
    }

    public function testFrom(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertEquals(1, $line->start()->x());
        $this->assertEquals(2, $line->start()->y());
        $result = $line->from(10, 20);
        $this->assertInstanceOf(Line::class, $result);
        $this->assertEquals(10, $line->start()->x());
        $this->assertEquals(20, $line->start()->y());
    }

    public function testTo(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertEquals(3, $line->end()->x());
        $this->assertEquals(4, $line->end()->y());
        $result = $line->to(30, 40);
        $this->assertInstanceOf(Line::class, $result);
        $this->assertEquals(30, $line->end()->x());
        $this->assertEquals(40, $line->end()->y());
    }

    public function testSetGetWidth(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertEquals(10, $line->width());
        $result = $line->setWidth(20);
        $this->assertInstanceOf(Line::class, $result);
        $this->assertEquals(20, $line->width());
    }

    public function testSetPosition(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertEquals(1, $line->position()->x());
        $this->assertEquals(2, $line->position()->y());
        $result = $line->setPosition(new Point(50, 60));
        $this->assertInstanceOf(Line::class, $result);
        $this->assertEquals(50, $line->position()->x());
        $this->assertEquals(60, $line->position()->y());
        // setPosition also changes start
        $this->assertEquals(50, $line->start()->x());
        $this->assertEquals(60, $line->start()->y());
    }

    public function testFactory(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $factory = $line->factory();
        $this->assertInstanceOf(LineFactory::class, $factory);
    }

    public function testClone(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $clone = clone $line;

        // cloned points are independent
        $this->assertEquals(1, $clone->start()->x());
        $this->assertEquals(2, $clone->start()->y());
        $this->assertEquals(3, $clone->end()->x());
        $this->assertEquals(4, $clone->end()->y());

        $clone->start()->setX(99);
        $this->assertEquals(99, $clone->start()->x());
        $this->assertEquals(1, $line->start()->x());
    }

    public function testCloneWithColors(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $bgColor = new Color(255, 0, 0);
        $borderColor = new Color(0, 255, 0);
        $line->setBackgroundColor($bgColor);
        $line->setBorderColor($borderColor);

        $clone = clone $line;

        // colors are cloned (independent instances)
        $this->assertNotSame($bgColor, $clone->backgroundColor());
        $this->assertNotSame($borderColor, $clone->borderColor());
        $this->assertInstanceOf(Color::class, $clone->backgroundColor());
        $this->assertInstanceOf(Color::class, $clone->borderColor());
    }
}
