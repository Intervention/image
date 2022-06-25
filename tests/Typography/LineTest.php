<?php

namespace Intervention\Image\Tests\Typography;

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
}
