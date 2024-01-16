<?php

declare(strict_types=1);

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
        $this->assertEquals(0, $line->position()->x());
        $this->assertEquals(0, $line->position()->y());

        $line->setPosition(new Point(10, 11));
        $this->assertEquals(10, $line->position()->x());
        $this->assertEquals(11, $line->position()->y());
    }
}
