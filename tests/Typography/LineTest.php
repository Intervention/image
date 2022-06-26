<?php

namespace Intervention\Image\Tests\Typography;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\Line;

class LineTest extends TestCase
{
    public function testConstructor(): void
    {
        $line = new Line('foo');
        $this->assertInstanceOf(Line::class, $line);
    }

    public function testToString(): void
    {
        $line = new Line('foo');
        $this->assertEquals('foo', (string) $line);
    }

    public function testSetGetPosition(): void
    {
        $line = new Line('foo');
        $this->assertEquals(0, $line->getPosition()->getX());
        $this->assertEquals(0, $line->getPosition()->getY());

        $line->setPosition(new Point(10, 11));
        $this->assertEquals(10, $line->getPosition()->getX());
        $this->assertEquals(11, $line->getPosition()->getY());
    }
}
