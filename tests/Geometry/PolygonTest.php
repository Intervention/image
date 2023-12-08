<?php

namespace Intervention\Image\Tests\Geometry;

use Intervention\Image\Geometry\{Point, Polygon};
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Geometry\Polygon
 */
class PolygonTest extends TestCase
{
    public function testConstructor(): void
    {
        $poly = new Polygon([]);
        $this->assertInstanceOf(Polygon::class, $poly);
        $this->assertEquals(0, $poly->count());
    }

    public function testCount(): void
    {
        $poly = new Polygon([new Point(), new Point()]);
        $this->assertEquals(2, $poly->count());
    }

    public function testArrayAccess(): void
    {
        $poly = new Polygon([new Point(), new Point()]);
        $this->assertInstanceOf(Point::class, $poly[0]);
        $this->assertInstanceOf(Point::class, $poly[1]);
    }

    public function testAddPoint(): void
    {
        $poly = new Polygon([new Point(), new Point()]);
        $this->assertEquals(2, $poly->count());
        $result = $poly->addPoint(new Point());
        $this->assertEquals(3, $poly->count());
        $this->assertInstanceOf(Polygon::class, $result);
    }

    public function testGetCenterPoint(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(20, 0),
            new Point(20, -20),
            new Point(0, -20),
        ]);

        $result = $poly->centerPoint();
        $this->assertEquals(10, $result->x());
        $this->assertEquals(-10, $result->y());

        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(0, 0));

        $result = $poly->centerPoint();
        $this->assertEquals(150, $result->x());
        $this->assertEquals(-100, $result->y());
    }

    public function testGetWidth(): void
    {
        $poly = new Polygon([
            new Point(12, 45),
            new Point(-23, -49),
            new Point(3, 566),
        ]);

        $this->assertEquals($poly->width(), 35);
    }

    public function testGetHeight(): void
    {
        $poly = new Polygon([
            new Point(12, 45),
            new Point(-23, -49),
            new Point(3, 566),
        ]);

        $this->assertEquals(615, $poly->height());

        $poly = new Polygon([
            new Point(250, 207),
            new Point(473, 207),
            new Point(473, 250),
            new Point(250, 250),
        ], new Point(250, 250));

        $this->assertEquals(43, $poly->height());
    }

    public function testFirst(): void
    {
        $poly = new Polygon([
            new Point(12, 45),
            new Point(-23, -49),
            new Point(3, 566),
        ]);

        $this->assertEquals(12, $poly->first()->x());
        $this->assertEquals(45, $poly->first()->y());
    }

    public function testLast(): void
    {
        $poly = new Polygon([
            new Point(12, 45),
            new Point(-23, -49),
            new Point(3, 566),
        ]);

        $this->assertEquals(3, $poly->last()->x());
        $this->assertEquals(566, $poly->last()->y());
    }

    public function testGetPivotPoint(): void
    {
        $poly = new Polygon();
        $this->assertInstanceOf(Point::class, $poly->pivot());
    }

    public function testGetMostLeftPoint(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(-32, -200),
        ], new Point(0, 0));

        $result = $poly->mostLeftPoint();
        $this->assertEquals(-32, $result->x());
        $this->assertEquals(-200, $result->y());
    }

    public function testGetMostRightPoint(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(350, 0),
            new Point(300, -200),
            new Point(-32, -200),
        ], new Point(0, 0));

        $result = $poly->mostRightPoint();
        $this->assertEquals(350, $result->x());
        $this->assertEquals(0, $result->y());
    }

    public function testGetMostTopPoint(): void
    {
        $poly = new Polygon([
            new Point(0, 100),
            new Point(350, 0),
            new Point(300, -200),
            new Point(-32, 200),
        ], new Point(0, 0));

        $result = $poly->mostTopPoint();
        $this->assertEquals(-32, $result->x());
        $this->assertEquals(200, $result->y());
    }

    public function testGetMostBottomPoint(): void
    {
        $poly = new Polygon([
            new Point(0, 100),
            new Point(350, 0),
            new Point(300, -200),
            new Point(-32, 200),
        ], new Point(0, 0));

        $result = $poly->mostBottomPoint();
        $this->assertEquals(300, $result->x());
        $this->assertEquals(-200, $result->y());
    }

    public function testAlignCenter(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(0, 0));

        $result = $poly->align('center');

        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-150, $result[0]->x());
        $this->assertEquals(0, $result[0]->y());
        $this->assertEquals(150, $result[1]->x());
        $this->assertEquals(0, $result[1]->y());
        $this->assertEquals(150, $result[2]->x());
        $this->assertEquals(-200, $result[2]->y());
        $this->assertEquals(-150, $result[3]->x());
        $this->assertEquals(-200, $result[3]->y());

        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(-1000, -1000));

        $result = $poly->align('center');

        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-1150, $result[0]->x());
        $this->assertEquals(0, $result[0]->y());
        $this->assertEquals(-850, $result[1]->x());
        $this->assertEquals(0, $result[1]->y());
        $this->assertEquals(-850, $result[2]->x());
        $this->assertEquals(-200, $result[2]->y());
        $this->assertEquals(-1150, $result[3]->x());
        $this->assertEquals(-200, $result[3]->y());
    }

    public function testAlignLeft(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->align('left');

        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(100, $result[0]->x());
        $this->assertEquals(0, $result[0]->y());
        $this->assertEquals(400, $result[1]->x());
        $this->assertEquals(0, $result[1]->y());
        $this->assertEquals(400, $result[2]->x());
        $this->assertEquals(-200, $result[2]->y());
        $this->assertEquals(100, $result[3]->x());
        $this->assertEquals(-200, $result[3]->y());

        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(-1000, -1000));

        $result = $poly->align('left');

        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-1000, $result[0]->x());
        $this->assertEquals(0, $result[0]->y());
        $this->assertEquals(-700, $result[1]->x());
        $this->assertEquals(0, $result[1]->y());
        $this->assertEquals(-700, $result[2]->x());
        $this->assertEquals(-200, $result[2]->y());
        $this->assertEquals(-1000, $result[3]->x());
        $this->assertEquals(-200, $result[3]->y());
    }

    public function testAlignRight(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->align('right');

        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-200, $result[0]->x());
        $this->assertEquals(0, $result[0]->y());
        $this->assertEquals(100, $result[1]->x());
        $this->assertEquals(0, $result[1]->y());
        $this->assertEquals(100, $result[2]->x());
        $this->assertEquals(-200, $result[2]->y());
        $this->assertEquals(-200, $result[3]->x());
        $this->assertEquals(-200, $result[3]->y());

        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(-1000, -1000));

        $result = $poly->align('right');

        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-1300, $result[0]->x());
        $this->assertEquals(0, $result[0]->y());
        $this->assertEquals(-1000, $result[1]->x());
        $this->assertEquals(0, $result[1]->y());
        $this->assertEquals(-1000, $result[2]->x());
        $this->assertEquals(-200, $result[2]->y());
        $this->assertEquals(-1300, $result[3]->x());
        $this->assertEquals(-200, $result[3]->y());
    }

    public function testValignMiddle(): void
    {
        $poly = new Polygon([
            new Point(-21, -22),
            new Point(91, -135),
            new Point(113, -113),
            new Point(0, 0),
        ], new Point(250, 250));

        $result = $poly->valign('middle');

        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-21, $result[0]->x());
        $this->assertEquals(296, $result[0]->y());
        $this->assertEquals(91, $result[1]->x());
        $this->assertEquals(183, $result[1]->y());
        $this->assertEquals(113, $result[2]->x());
        $this->assertEquals(205, $result[2]->y());
        $this->assertEquals(0, $result[3]->x());
        $this->assertEquals(318, $result[3]->y());
    }

    public function testRotate(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(50, 0),
            new Point(50, -50),
            new Point(0, -50),
        ]);

        $result = $poly->rotate(45);
        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(0, $result[0]->x());
        $this->assertEquals(0, $result[0]->y());
        $this->assertEquals(35, $result[1]->x());
        $this->assertEquals(35, $result[1]->y());
        $this->assertEquals(70, $result[2]->x());
        $this->assertEquals(0, $result[2]->y());
        $this->assertEquals(35, $result[3]->x());
        $this->assertEquals(-35, $result[3]->y());
    }

    public function testToArray(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(50, 0),
            new Point(50, -50),
            new Point(0, -50),
        ]);

        $this->assertEquals([0, 0, 50, 0, 50, -50, 0, -50], $poly->toArray());
    }
}
