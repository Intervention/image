<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\PointInterface;
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

    public function testSetSize(): void
    {
        $rectangle = new Rectangle(300, 200);
        $rectangle->setSize(12, 34);
        $this->assertEquals(12, $rectangle->width());
        $this->assertEquals(34, $rectangle->height());
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
        $size = new Rectangle(800, 600);
        $this->assertEquals(1.333, round($size->aspectRatio(), 3));

        $size = new Rectangle(100, 100);
        $this->assertEquals(1, $size->aspectRatio());

        $size = new Rectangle(1920, 1080);
        $this->assertEquals(1.778, round($size->aspectRatio(), 3));
    }

    public function testFitsInto(): void
    {
        $box = new Rectangle(800, 600);
        $fits = $box->fitsInto(new Rectangle(100, 100));
        $this->assertFalse($fits);

        $box = new Rectangle(800, 600);
        $fits = $box->fitsInto(new Rectangle(1000, 100));
        $this->assertFalse($fits);

        $box = new Rectangle(800, 600);
        $fits = $box->fitsInto(new Rectangle(100, 1000));
        $this->assertFalse($fits);

        $box = new Rectangle(800, 600);
        $fits = $box->fitsInto(new Rectangle(800, 600));
        $this->assertTrue($fits);

        $box = new Rectangle(800, 600);
        $fits = $box->fitsInto(new Rectangle(1000, 1000));
        $this->assertTrue($fits);

        $box = new Rectangle(100, 100);
        $fits = $box->fitsInto(new Rectangle(800, 600));
        $this->assertTrue($fits);

        $box = new Rectangle(100, 100);
        $fits = $box->fitsInto(new Rectangle(80, 60));
        $this->assertFalse($fits);
    }

    public function testIsLandscape(): void
    {
        $box = new Rectangle(100, 100);
        $this->assertFalse($box->isLandscape());

        $box = new Rectangle(100, 200);
        $this->assertFalse($box->isLandscape());

        $box = new Rectangle(300, 200);
        $this->assertTrue($box->isLandscape());
    }

    public function testIsPortrait(): void
    {
        $box = new Rectangle(100, 100);
        $this->assertFalse($box->isPortrait());

        $box = new Rectangle(200, 100);
        $this->assertFalse($box->isPortrait());

        $box = new Rectangle(200, 300);
        $this->assertTrue($box->isPortrait());
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

    public function testAlignPivot(): void
    {
        $box = new Rectangle(640, 480);
        $this->assertEquals(0, $box->pivot()->x());
        $this->assertEquals(0, $box->pivot()->y());

        $box->movePivot('top-left', 3, 3);
        $this->assertEquals(3, $box->pivot()->x());
        $this->assertEquals(3, $box->pivot()->y());

        $box->movePivot('top', 3, 3);
        $this->assertEquals(323, $box->pivot()->x());
        $this->assertEquals(3, $box->pivot()->y());

        $box->movePivot('top-right', 3, 3);
        $this->assertEquals(637, $box->pivot()->x());
        $this->assertEquals(3, $box->pivot()->y());

        $box->movePivot('left', 3, 3);
        $this->assertEquals(3, $box->pivot()->x());
        $this->assertEquals(243, $box->pivot()->y());

        $box->movePivot('center', 3, 3);
        $this->assertEquals(323, $box->pivot()->x());
        $this->assertEquals(243, $box->pivot()->y());

        $box->movePivot('right', 3, 3);
        $this->assertEquals(637, $box->pivot()->x());
        $this->assertEquals(243, $box->pivot()->y());

        $box->movePivot('bottom-left', 3, 3);
        $this->assertEquals(3, $box->pivot()->x());
        $this->assertEquals(477, $box->pivot()->y());

        $box->movePivot('bottom', 3, 3);
        $this->assertEquals(323, $box->pivot()->x());
        $this->assertEquals(477, $box->pivot()->y());

        $result = $box->movePivot('bottom-right', 3, 3);
        $this->assertEquals(637, $box->pivot()->x());
        $this->assertEquals(477, $box->pivot()->y());

        $this->assertInstanceOf(Rectangle::class, $result);
    }

    public function testAlignPivotTo(): void
    {
        $container = new Rectangle(800, 600);
        $size = new Rectangle(200, 100);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(300, $size->pivot()->x());
        $this->assertEquals(250, $size->pivot()->y());

        $container = new Rectangle(800, 600);
        $size = new Rectangle(100, 100);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(350, $size->pivot()->x());
        $this->assertEquals(250, $size->pivot()->y());

        $container = new Rectangle(800, 600);
        $size = new Rectangle(800, 600);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(0, $size->pivot()->x());
        $this->assertEquals(0, $size->pivot()->y());

        $container = new Rectangle(100, 100);
        $size = new Rectangle(800, 600);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(-350, $size->pivot()->x());
        $this->assertEquals(-250, $size->pivot()->y());

        $container = new Rectangle(100, 100);
        $size = new Rectangle(800, 600);
        $size->alignPivotTo($container, 'bottom-right');
        $this->assertEquals(-700, $size->pivot()->x());
        $this->assertEquals(-500, $size->pivot()->y());
    }

    public function testgetRelativePositionTo(): void
    {
        $container = new Rectangle(800, 600);
        $input = new Rectangle(200, 100);
        $container->movePivot('top-left');
        $input->movePivot('top-left');
        $pos = $container->relativePositionTo($input);
        $this->assertEquals(0, $pos->x());
        $this->assertEquals(0, $pos->y());

        $container = new Rectangle(800, 600);
        $input = new Rectangle(200, 100);
        $container->movePivot('center');
        $input->movePivot('top-left');
        $pos = $container->relativePositionTo($input);
        $this->assertEquals(400, $pos->x());
        $this->assertEquals(300, $pos->y());

        $container = new Rectangle(800, 600);
        $input = new Rectangle(200, 100);
        $container->movePivot('bottom-right');
        $input->movePivot('top-right');
        $pos = $container->relativePositionTo($input);
        $this->assertEquals(600, $pos->x());
        $this->assertEquals(600, $pos->y());

        $container = new Rectangle(800, 600);
        $input = new Rectangle(200, 100);
        $container->movePivot('center');
        $input->movePivot('center');
        $pos = $container->relativePositionTo($input);
        $this->assertEquals(300, $pos->x());
        $this->assertEquals(250, $pos->y());

        $container = new Rectangle(100, 200);
        $input = new Rectangle(100, 100);
        $container->movePivot('center');
        $input->movePivot('center');
        $pos = $container->relativePositionTo($input);
        $this->assertEquals(0, $pos->x());
        $this->assertEquals(50, $pos->y());
    }

    public function testTopLeftPoint(): void
    {
        $rectangle = new Rectangle(800, 600);
        $this->assertInstanceOf(PointInterface::class, $rectangle->topLeftPoint());
    }

    public function testBottomRightPoint(): void
    {
        $rectangle = new Rectangle(800, 600);
        $this->assertInstanceOf(PointInterface::class, $rectangle->bottomRightPoint());
    }

    public function testResize(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->resize(300, 200);
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(300, $result->width());
        $this->assertEquals(200, $result->height());
    }

    public function testResizeDown(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->resizeDown(3000, 200);
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(200, $result->height());
    }

    public function testScale(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->scale(height: 1200);
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(800 * 2, $result->width());
        $this->assertEquals(600 * 2, $result->height());
    }

    public function testScaleDown(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->scaleDown(height: 1200);
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testCover(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->cover(400, 100);
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testContain(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->contain(1600, 1200);
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(1600, $result->width());
        $this->assertEquals(1200, $result->height());
    }

    public function testContainMax(): void
    {
        $rectangle = new Rectangle(800, 600);
        $result = $rectangle->containMax(1600, 1200);
        $this->assertInstanceOf(Rectangle::class, $result);
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
