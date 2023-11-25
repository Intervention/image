<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Drivers\Gd\FontProcessor;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\Font;

class FontProcessorTest extends TestCase
{
    public function testBoxSize(): void
    {
        $processor = new FontProcessor(new Font());
        $result = $processor->boxSize('test');
        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(20, $result->width());
        $this->assertEquals(8, $result->height());
    }

    public function testAdjustedSize(): void
    {
        $processor = new FontProcessor((new Font())->setSize(100));
        $this->assertEquals(75, $processor->adjustedSize());
    }

    public function testGetGdFont(): void
    {
        $processor = new FontProcessor(new Font());
        $this->assertEquals(1, $processor->getGdFont());

        $processor = new FontProcessor((new Font())->setFilename(100));
        $this->assertEquals(100, $processor->getGdFont());

        $processor = new FontProcessor((new Font())->setFilename('foo'));
        $this->assertEquals(1, $processor->getGdFont());
    }
}
