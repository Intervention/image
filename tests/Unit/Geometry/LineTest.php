<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

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

    public function setFrom(): void
    {
        $line = new Line(new Point(1, 2), new Point(3, 4), 10);
        $this->assertEquals(1, $line->start()->x());
        $this->assertEquals(2, $line->start()->y());
        $result = $line->from(10, 20);
        $this->assertInstanceOf(Line::class, $result);
        $this->assertEquals(10, $line->start()->x());
        $this->assertEquals(20, $line->start()->y());
    }

    public function setTo(): void
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
}
