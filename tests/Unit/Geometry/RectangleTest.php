<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Alignment;
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

    public function testGetAspectRatio(): void
    {
        $rectangle = new Rectangle(800, 600);
        $this->assertEquals(1.333, round($rectangle->aspectRatio(), 3));

        $rectangle = new Rectangle(100, 100);
        $this->assertEquals(1, $rectangle->aspectRatio());

        $rectangle = new Rectangle(1920, 1080);
        $this->assertEquals(1.778, round($rectangle->aspectRatio(), 3));
    }

    public function testFitsInto(): void
    {
        $rectangle = new Rectangle(800, 600);
        $fits = $rectangle->fitsWithin(new Rectangle(100, 100));
        $this->assertFalse($fits);

        $rectangle = new Rectangle(800, 600);
        $fits = $rectangle->fitsWithin(new Rectangle(1000, 100));
        $this->assertFalse($fits);

        $rectangle = new Rectangle(800, 600);
        $fits = $rectangle->fitsWithin(new Rectangle(100, 1000));
        $this->assertFalse($fits);

        $rectangle = new Rectangle(800, 600);
        $fits = $rectangle->fitsWithin(new Rectangle(800, 600));
        $this->assertTrue($fits);

        $rectangle = new Rectangle(800, 600);
        $fits = $rectangle->fitsWithin(new Rectangle(1000, 1000));
        $this->assertTrue($fits);

        $rectangle = new Rectangle(100, 100);
        $fits = $rectangle->fitsWithin(new Rectangle(800, 600));
        $this->assertTrue($fits);

        $rectangle = new Rectangle(100, 100);
        $fits = $rectangle->fitsWithin(new Rectangle(80, 60));
        $this->assertFalse($fits);
    }

    public function testIsLandscape(): void
    {
        $rectangle = new Rectangle(100, 100);
        $this->assertFalse($rectangle->isLandscape());

        $rectangle = new Rectangle(100, 200);
        $this->assertFalse($rectangle->isLandscape());

        $rectangle = new Rectangle(300, 200);
        $this->assertTrue($rectangle->isLandscape());
    }

    public function testIsPortrait(): void
    {
        $rectangle = new Rectangle(100, 100);
        $this->assertFalse($rectangle->isPortrait());

        $rectangle = new Rectangle(200, 100);
        $this->assertFalse($rectangle->isPortrait());

        $rectangle = new Rectangle(200, 300);
        $this->assertTrue($rectangle->isPortrait());
    }

    public function testSetGetPivot(): void
    {
        $rectangle = new Rectangle(800, 600);
        $pivot = $rectangle->pivot();
        $this->assertInstanceOf(Point::class, $pivot);
        $this->assertEquals(0, $pivot->x());
        $result = $rectangle->setPivot(new Point(10, 0));
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(10, $rectangle->pivot()->x());
    }

    public function testAlignPivot(): void
    {
        $rectangle = new Rectangle(640, 480);
        $this->assertEquals(0, $rectangle->pivot()->x());
        $this->assertEquals(0, $rectangle->pivot()->y());

        $rectangle->movePivot(Alignment::TOP_LEFT, 3, 3);
        $this->assertEquals(3, $rectangle->pivot()->x());
        $this->assertEquals(3, $rectangle->pivot()->y());

        $rectangle->movePivot(Alignment::TOP, 3, 3);
        $this->assertEquals(323, $rectangle->pivot()->x());
        $this->assertEquals(3, $rectangle->pivot()->y());

        $rectangle->movePivot(Alignment::TOP_RIGHT, 3, 3);
        $this->assertEquals(637, $rectangle->pivot()->x());
        $this->assertEquals(3, $rectangle->pivot()->y());

        $rectangle->movePivot(Alignment::LEFT, 3, 3);
        $this->assertEquals(3, $rectangle->pivot()->x());
        $this->assertEquals(243, $rectangle->pivot()->y());

        $rectangle->movePivot(Alignment::CENTER, 3, 3);
        $this->assertEquals(323, $rectangle->pivot()->x());
        $this->assertEquals(243, $rectangle->pivot()->y());

        $rectangle->movePivot(Alignment::RIGHT, 3, 3);
        $this->assertEquals(637, $rectangle->pivot()->x());
        $this->assertEquals(243, $rectangle->pivot()->y());

        $rectangle->movePivot(Alignment::BOTTOM_LEFT, 3, 3);
        $this->assertEquals(3, $rectangle->pivot()->x());
        $this->assertEquals(477, $rectangle->pivot()->y());

        $rectangle->movePivot(Alignment::BOTTOM, 3, 3);
        $this->assertEquals(323, $rectangle->pivot()->x());
        $this->assertEquals(477, $rectangle->pivot()->y());

        $result = $rectangle->movePivot(Alignment::BOTTOM_RIGHT, 3, 3);
        $this->assertEquals(637, $rectangle->pivot()->x());
        $this->assertEquals(477, $rectangle->pivot()->y());

        $this->assertInstanceOf(Rectangle::class, $result);
    }

    public function testAlignPivotTo(): void
    {
        $container = new Rectangle(800, 600);
        $rectangle = new Rectangle(200, 100);
        $rectangle->alignPivotTo($container, Alignment::CENTER);
        $this->assertEquals(300, $rectangle->pivot()->x());
        $this->assertEquals(250, $rectangle->pivot()->y());

        $container = new Rectangle(800, 600);
        $rectangle = new Rectangle(100, 100);
        $rectangle->alignPivotTo($container, Alignment::CENTER);
        $this->assertEquals(350, $rectangle->pivot()->x());
        $this->assertEquals(250, $rectangle->pivot()->y());

        $container = new Rectangle(800, 600);
        $rectangle = new Rectangle(800, 600);
        $rectangle->alignPivotTo($container, Alignment::CENTER);
        $this->assertEquals(0, $rectangle->pivot()->x());
        $this->assertEquals(0, $rectangle->pivot()->y());

        $container = new Rectangle(100, 100);
        $rectangle = new Rectangle(800, 600);
        $rectangle->alignPivotTo($container, Alignment::CENTER);
        $this->assertEquals(-350, $rectangle->pivot()->x());
        $this->assertEquals(-250, $rectangle->pivot()->y());

        $container = new Rectangle(100, 100);
        $rectangle = new Rectangle(800, 600);
        $rectangle->alignPivotTo($container, Alignment::BOTTOM_RIGHT);
        $this->assertEquals(-700, $rectangle->pivot()->x());
        $this->assertEquals(-500, $rectangle->pivot()->y());
    }

    public function testOffsetTo(): void
    {
        $container = new Rectangle(800, 600);
        $rectangle = new Rectangle(200, 100);
        $container->movePivot(Alignment::TOP_LEFT);
        $rectangle->movePivot(Alignment::TOP_LEFT);
        $pos = $container->offsetTo($rectangle);
        $this->assertEquals(0, $pos->x());
        $this->assertEquals(0, $pos->y());

        $container = new Rectangle(800, 600);
        $rectangle = new Rectangle(200, 100);
        $container->movePivot(Alignment::CENTER);
        $rectangle->movePivot(Alignment::TOP_LEFT);
        $pos = $container->offsetTo($rectangle);
        $this->assertEquals(400, $pos->x());
        $this->assertEquals(300, $pos->y());

        $container = new Rectangle(800, 600);
        $rectangle = new Rectangle(200, 100);
        $container->movePivot(Alignment::BOTTOM_RIGHT);
        $rectangle->movePivot(Alignment::TOP_RIGHT);
        $pos = $container->offsetTo($rectangle);
        $this->assertEquals(600, $pos->x());
        $this->assertEquals(600, $pos->y());

        $container = new Rectangle(800, 600);
        $rectangle = new Rectangle(200, 100);
        $container->movePivot(Alignment::CENTER);
        $rectangle->movePivot(Alignment::CENTER);
        $pos = $container->offsetTo($rectangle);
        $this->assertEquals(300, $pos->x());
        $this->assertEquals(250, $pos->y());

        $container = new Rectangle(100, 200);
        $rectangle = new Rectangle(100, 100);
        $container->movePivot(Alignment::CENTER);
        $rectangle->movePivot(Alignment::CENTER);
        $pos = $container->offsetTo($rectangle);
        $this->assertEquals(0, $pos->x());
        $this->assertEquals(50, $pos->y());
    }

    public function testResize(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->resize(300, 200);
        $this->assertEquals(300, $result->width());
        $this->assertEquals(200, $result->height());
    }

    public function testResizeDown(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->resizeDown(3000, 200);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(200, $result->height());
    }

    public function testScale(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->scale(height: 1200);
        $this->assertEquals(800 * 2, $result->width());
        $this->assertEquals(600 * 2, $result->height());
    }

    public function testScaleDown(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->scaleDown(height: 1200);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testCover(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->cover(400, 100);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testContain(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->contain(1600, 1200);
        $this->assertEquals(1600, $result->width());
        $this->assertEquals(1200, $result->height());
    }

    public function testContainDown(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->containDown(1600, 1200);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testDebugInfo(): void
    {
        $info = (new Rectangle(800, 600))->__debugInfo();
        $this->assertEquals(800, $info['width']);
        $this->assertEquals(600, $info['height']);
    }
}
