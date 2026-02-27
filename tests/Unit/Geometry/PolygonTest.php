<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Alignment;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Geometry\Factories\PolygonFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Polygon::class)]
final class PolygonTest extends BaseTestCase
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

    public function testFirstEmpty(): void
    {
        $poly = new Polygon();
        $this->assertNull($poly->first());
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

    public function testLastEmpty(): void
    {
        $poly = new Polygon();
        $this->assertNull($poly->last());
    }

    public function testOffsetExists(): void
    {
        $poly = new Polygon();
        $this->assertFalse($poly->offsetExists(0));
        $this->assertFalse($poly->offsetExists(1));
        $poly->addPoint(new Point(0, 0));
        $this->assertTrue($poly->offsetExists(0));
        $this->assertFalse($poly->offsetExists(1));
    }

    public function testOffsetSetUnset(): void
    {
        $poly = new Polygon();
        $poly->offsetSet(0, new Point());
        $poly->offsetSet(2, new Point());
        $this->assertTrue($poly->offsetExists(0));
        $this->assertFalse($poly->offsetExists(1));
        $this->assertTrue($poly->offsetExists(2));
        $poly->offsetUnset(2);
        $this->assertTrue($poly->offsetExists(0));
        $this->assertFalse($poly->offsetExists(1));
        $this->assertFalse($poly->offsetExists(2));
    }

    public function testGetSetPivotPoint(): void
    {
        $poly = new Polygon();
        $this->assertInstanceOf(Point::class, $poly->pivot());
        $this->assertEquals(0, $poly->pivot()->x());
        $this->assertEquals(0, $poly->pivot()->y());
        $result = $poly->setPivot(new Point(12, 34));
        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(12, $poly->pivot()->x());
        $this->assertEquals(34, $poly->pivot()->y());
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

    public function testAlignHorizontallyCenter(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(0, 0));

        $result = $poly->alignHorizontally(Alignment::CENTER);

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

        $result = $poly->alignHorizontally(Alignment::CENTER);

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

    public function testAlignHorizontallyLeft(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignHorizontally(Alignment::LEFT);

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

        $result = $poly->alignHorizontally(Alignment::LEFT);

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

    public function testAlignHorizontallyRight(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignHorizontally(Alignment::RIGHT);

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

        $result = $poly->alignHorizontally(Alignment::RIGHT);

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

    public function testAlignVerticallyMiddle(): void
    {
        $poly = new Polygon([
            new Point(-21, -22),
            new Point(91, -135),
            new Point(113, -113),
            new Point(0, 0),
        ], new Point(250, 250));

        $result = $poly->alignVertically('middle');

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

    public function testAlignVerticallyTop(): void
    {
        $poly = new Polygon([
            new Point(-21, -22),
            new Point(91, -135),
            new Point(113, -113),
            new Point(0, 0),
        ], new Point(250, 250));

        $result = $poly->alignVertically(Alignment::TOP);

        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-21, $result[0]->x());
        $this->assertEquals(363, $result[0]->y());
        $this->assertEquals(91, $result[1]->x());
        $this->assertEquals(250, $result[1]->y());
        $this->assertEquals(113, $result[2]->x());
        $this->assertEquals(272, $result[2]->y());
        $this->assertEquals(0, $result[3]->x());
        $this->assertEquals(385, $result[3]->y());
    }

    public function testMovePoints(): void
    {
        $poly = new Polygon([
            new Point(10, 20),
            new Point(30, 40)
        ]);

        $result = $poly->movePointsX(100);
        $this->assertEquals(110, $result[0]->x());
        $this->assertEquals(20, $result[0]->y());
        $this->assertEquals(130, $result[1]->x());
        $this->assertEquals(40, $result[1]->y());

        $result = $poly->movePointsY(200);
        $this->assertEquals(110, $result[0]->x());
        $this->assertEquals(220, $result[0]->y());
        $this->assertEquals(130, $result[1]->x());
        $this->assertEquals(240, $result[1]->y());
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
        $poly = new Polygon([new Point(0, 0), new Point(50, 0), new Point(50, -50), new Point(0, -50)]);
        $this->assertEquals([0, 0, 50, 0, 50, -50, 0, -50], $poly->toArray());
    }

    public function testAlignVerticallyBottom(): void
    {
        $poly = new Polygon([
            new Point(-21, -22),
            new Point(91, -135),
            new Point(113, -113),
            new Point(0, 0),
        ], new Point(250, 250));

        $result = $poly->alignVertically(Alignment::BOTTOM);

        $this->assertInstanceOf(Polygon::class, $result);
        // After bottom alignment, the most bottom point should be shifted relative to pivot + height
    }

    public function testAlignVerticallyBottomRight(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignVertically(Alignment::BOTTOM_RIGHT);
        $this->assertInstanceOf(Polygon::class, $result);
    }

    public function testAlignVerticallyTopLeft(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignVertically(Alignment::TOP_LEFT);
        $this->assertInstanceOf(Polygon::class, $result);
    }

    public function testAlignVerticallyTopRight(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignVertically(Alignment::TOP_RIGHT);
        $this->assertInstanceOf(Polygon::class, $result);
    }

    public function testAlignVerticallyBottomLeft(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignVertically(Alignment::BOTTOM_LEFT);
        $this->assertInstanceOf(Polygon::class, $result);
    }

    public function testAlignHorizontallyTopLeft(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignHorizontally(Alignment::TOP_LEFT);
        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(100, $result[0]->x());
    }

    public function testAlignHorizontallyBottomLeft(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignHorizontally(Alignment::BOTTOM_LEFT);
        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(100, $result[0]->x());
    }

    public function testAlignHorizontallyTopRight(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignHorizontally(Alignment::TOP_RIGHT);
        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-200, $result[0]->x());
    }

    public function testAlignHorizontallyBottomRight(): void
    {
        $poly = new Polygon([
            new Point(0, 0),
            new Point(300, 0),
            new Point(300, -200),
            new Point(0, -200),
        ], new Point(100, 100));

        $result = $poly->alignHorizontally(Alignment::BOTTOM_RIGHT);
        $this->assertInstanceOf(Polygon::class, $result);
        $this->assertEquals(-200, $result[0]->x());
    }

    public function testGetIterator(): void
    {
        $poly = new Polygon([new Point(10, 20), new Point(30, 40)]);
        $points = iterator_to_array($poly);
        $this->assertCount(2, $points);
        $this->assertEquals(10, $points[0]->x());
        $this->assertEquals(30, $points[1]->x());
    }

    public function testPosition(): void
    {
        $poly = new Polygon([], new Point(50, 60));
        $pos = $poly->position();
        $this->assertEquals(50, $pos->x());
        $this->assertEquals(60, $pos->y());
    }

    public function testSetPosition(): void
    {
        $poly = new Polygon();
        $result = $poly->setPosition(new Point(100, 200));
        $this->assertSame($poly, $result);
        $this->assertEquals(100, $poly->position()->x());
        $this->assertEquals(200, $poly->position()->y());
    }

    public function testFactory(): void
    {
        $poly = new Polygon([new Point(0, 0), new Point(100, 0), new Point(50, -50)]);
        $factory = $poly->factory();
        $this->assertInstanceOf(PolygonFactory::class, $factory);
    }

    public function testClone(): void
    {
        $poly = new Polygon([new Point(10, 20), new Point(30, 40)], new Point(5, 5));
        $clone = clone $poly;

        // Points should be independent
        $this->assertEquals(10, $clone[0]->x());
        $clone[0]->setX(999);
        $this->assertEquals(10, $poly[0]->x());

        // Pivot should be independent
        $this->assertEquals(5, $poly->pivot()->x());
        $clone->setPivot(new Point(888, 888));
        $this->assertEquals(5, $poly->pivot()->x());
    }

    public function testCloneWithBackgroundColor(): void
    {
        $poly = new Polygon([new Point(10, 20)]);
        $color = new RgbColor(
            new Red(255),
            new Green(0),
            new Blue(0),
            new Alpha(1)
        );
        $poly->setBackgroundColor($color);
        $clone = clone $poly;

        // Background color should be cloned independently
        $this->assertNotSame($poly->backgroundColor(), $clone->backgroundColor());
    }

    public function testCloneWithBorderColor(): void
    {
        $poly = new Polygon([new Point(10, 20)]);
        $color = new RgbColor(
            new Red(0),
            new Green(255),
            new Blue(0),
            new Alpha(1)
        );
        $poly->setBorderColor($color);
        $clone = clone $poly;

        // Border color should be cloned independently
        $this->assertNotSame($poly->borderColor(), $clone->borderColor());
    }
}
