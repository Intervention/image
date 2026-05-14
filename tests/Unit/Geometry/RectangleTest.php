<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Rectangle::class)]
final class RectangleTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(0, $rectangle[0]->x());
        $this->assertEquals(0, $rectangle[0]->y());
        $this->assertEquals(300, $rectangle[1]->x());
        $this->assertEquals(0, $rectangle[1]->y());
        $this->assertEquals(300, $rectangle[2]->x());
        $this->assertEquals(-200, $rectangle[2]->y());
        $this->assertEquals(0, $rectangle[3]->x());
        $this->assertEquals(-200, $rectangle[3]->y());
        $this->assertEquals(300, $rectangle->width());
        $this->assertEquals(200, $rectangle->height());
    }

    public function testFactory(): void
    {
        $rectangle = new Rectangle(300, 200);
        $factory = $rectangle->factory();
        $this->assertInstanceOf(RectangleFactory::class, $factory);
    }

    public function testAdjust(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(null, $rectangle->backgroundColor());
        $adjusted = $rectangle->adjust(fn(RectangleFactory $factory) => $factory->background('f50'));
        $this->assertEquals(null, $rectangle->backgroundColor());
        $this->assertEquals('f50', $adjusted->backgroundColor());
    }

    public function testSetWidth(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(300, $rectangle->width());
        $rectangle->setWidth(400);
        $this->assertEquals(400, $rectangle->width());
    }

    public function testSetHeight(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(200, $rectangle->height());
        $rectangle->setHeight(800);
        $this->assertEquals(800, $rectangle->height());
    }

    public function testSetGetPivot(): void
    {
        $box = new Rectangle(800, 600);
        $pivot = $box->pivot();
        $this->assertInstanceOf(Point::class, $pivot);
        $this->assertEquals(0, $pivot->x());
        $result = $box->setPivot(new Point(10, 0));
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(10, $box->pivot()->x());
    }
}
