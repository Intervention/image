<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Typography;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Line;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Line::class)]
final class LineTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $line = new Line('foo');
        $this->assertInstanceOf(Line::class, $line);
    }

    public function testToString(): void
    {
        $line = new Line('foo bar');
        $this->assertEquals('foo bar', (string) $line);
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

    public function testCount(): void
    {
        $line = new Line();
        $this->assertEquals(0, $line->count());

        $line = new Line("foo");
        $this->assertEquals(1, $line->count());

        $line = new Line("foo bar");
        $this->assertEquals(2, $line->count());
    }

    public function testLength(): void
    {
        $line = new Line();
        $this->assertEquals(0, $line->length());

        $line = new Line("foo");
        $this->assertEquals(3, $line->length());

        $line = new Line("foo bar.");
        $this->assertEquals(8, $line->length());

        $line = new Line("🫷🙂🫸");
        $this->assertEquals(3, $line->length());
    }

    public function testAdd(): void
    {
        $line = new Line();
        $this->assertEquals(0, $line->count());

        $result = $line->add('foo');
        $this->assertEquals(1, $line->count());
        $this->assertEquals(1, $result->count());

        $result = $line->add('bar');
        $this->assertEquals(2, $line->count());
        $this->assertEquals(2, $result->count());
    }
}
