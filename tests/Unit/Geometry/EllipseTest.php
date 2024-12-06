<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Ellipse::class)]
final class EllipseTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $this->assertInstanceOf(Ellipse::class, $ellipse);
        $this->assertEquals(10, $ellipse->width());
        $this->assertEquals(20, $ellipse->height());
    }

    public function testPosition(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $this->assertInstanceOf(Point::class, $ellipse->position());
        $this->assertEquals(100, $ellipse->position()->x());
        $this->assertEquals(200, $ellipse->position()->y());

        $this->assertInstanceOf(Point::class, $ellipse->pivot());
        $this->assertEquals(100, $ellipse->pivot()->x());
        $this->assertEquals(200, $ellipse->pivot()->y());
    }

    public function testSetSize(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $this->assertEquals(10, $ellipse->width());
        $this->assertEquals(20, $ellipse->height());
        $result = $ellipse->setSize(100, 200);
        $this->assertInstanceOf(Ellipse::class, $result);
        $this->assertEquals(100, $ellipse->width());
        $this->assertEquals(200, $ellipse->height());
    }

    public function testSetWidthHeight(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $this->assertEquals(10, $ellipse->width());
        $this->assertEquals(20, $ellipse->height());
        $result = $ellipse->setWidth(100);
        $this->assertInstanceOf(Ellipse::class, $result);
        $this->assertEquals(100, $ellipse->width());
        $this->assertEquals(20, $ellipse->height());
        $result = $ellipse->setHeight(200);
        $this->assertInstanceOf(Ellipse::class, $result);
        $this->assertEquals(100, $ellipse->width());
        $this->assertEquals(200, $ellipse->height());
    }
}
