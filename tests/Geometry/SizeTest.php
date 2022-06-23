<?php

namespace Intervention\Image\Tests\Geometry;

use Intervention\Image\Geometry\{Point, Polygon, Size,};
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Geometry\Size
 */
class SizeTest extends TestCase
{
    public function testConstructor()
    {
        $pivot = new Point(10, 20);
        $size = new Size(300, 200, $pivot);
        $this->assertInstanceOf(Size::class, $size);
        $this->assertInstanceOf(Point::class, $size->getPivot());
        $this->assertEquals(300, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());
    }

    public function testCompareSizes(): void
    {
        $size1 = new Size(300, 200);
        $size2 = new Size(300, 200);
        $size2a = new Size(300, 200, new Point(1, 1));
        $size2b = new Size(300, 200, new Point(1, 1));
        $size3 = new Size(300, 201);
        $size4 = new Size(301, 200);

        $this->assertTrue($size1 == $size2);
        $this->assertTrue($size2a == $size2b);
        $this->assertFalse($size2 == $size2a);
        $this->assertFalse($size2 == $size3);
        $this->assertFalse($size2 == $size4);
        $this->assertFalse($size3 == $size4);
    }

    public function testSetGetWidth()
    {
        $size = new Size(800, 600);
        $this->assertEquals(800, $size->getWidth());
        $result = $size->setWidth(30);
        $this->assertEquals(30, $size->getWidth());
        $this->assertInstanceOf(Size::class, $result);
    }

    public function testSetGetHeight()
    {
        $size = new Size(800, 600);
        $this->assertEquals(600, $size->getHeight());
        $result = $size->setHeight(30);
        $this->assertEquals(30, $size->getHeight());
        $this->assertInstanceOf(Size::class, $result);
    }

    public function testSetGetPivot(): void
    {
        $size = new Size(800, 600);
        $pivot = $size->getPivot();
        $this->assertInstanceOf(Point::class, $pivot);
        $this->assertEquals(0, $pivot->getX());
        $result = $size->setPivot(new Point(10, 0));
        $this->assertInstanceOf(Size::class, $result);
        $this->assertEquals(10, $size->getPivot()->getX());
    }

    public function testGetAspectRatio()
    {
        $size = new Size(800, 600);
        $this->assertEquals(1.33333333333, $size->getAspectRatio());

        $size = new Size(100, 100);
        $this->assertEquals(1, $size->getAspectRatio());

        $size = new Size(1920, 1080);
        $this->assertEquals(1.777777777778, $size->getAspectRatio());
    }

    public function testFitsInto()
    {
        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(100, 100));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(1000, 100));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(100, 1000));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(800, 600));
        $this->assertTrue($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(1000, 1000));
        $this->assertTrue($fits);

        $box = new Size(100, 100);
        $fits = $box->fitsInto(new Size(800, 600));
        $this->assertTrue($fits);

        $box = new Size(100, 100);
        $fits = $box->fitsInto(new Size(80, 60));
        $this->assertFalse($fits);
    }

    public function testIsLandscape()
    {
        $box = new Size(100, 100);
        $this->assertFalse($box->isLandscape());

        $box = new Size(100, 200);
        $this->assertFalse($box->isLandscape());

        $box = new Size(300, 200);
        $this->assertTrue($box->isLandscape());
    }

    public function testIsPortrait()
    {
        $box = new Size(100, 100);
        $this->assertFalse($box->isPortrait());

        $box = new Size(200, 100);
        $this->assertFalse($box->isPortrait());

        $box = new Size(200, 300);
        $this->assertTrue($box->isPortrait());
    }

    public function testAlignPivot(): void
    {
        $box = new Size(640, 480);
        $this->assertEquals(0, $box->getPivot()->getX());
        $this->assertEquals(0, $box->getPivot()->getY());

        $box->alignPivot('top-left', 3, 3);
        $this->assertEquals(3, $box->getPivot()->getX());
        $this->assertEquals(3, $box->getPivot()->getY());

        $box->alignPivot('top', 3, 3);
        $this->assertEquals(320, $box->getPivot()->getX());
        $this->assertEquals(3, $box->getPivot()->getY());

        $box->alignPivot('top-right', 3, 3);
        $this->assertEquals(637, $box->getPivot()->getX());
        $this->assertEquals(3, $box->getPivot()->getY());

        $box->alignPivot('left', 3, 3);
        $this->assertEquals(3, $box->getPivot()->getX());
        $this->assertEquals(240, $box->getPivot()->getY());

        $box->alignPivot('center', 3, 3);
        $this->assertEquals(323, $box->getPivot()->getX());
        $this->assertEquals(243, $box->getPivot()->getY());

        $box->alignPivot('right', 3, 3);
        $this->assertEquals(637, $box->getPivot()->getX());
        $this->assertEquals(240, $box->getPivot()->getY());

        $box->alignPivot('bottom-left', 3, 3);
        $this->assertEquals(3, $box->getPivot()->getX());
        $this->assertEquals(477, $box->getPivot()->getY());

        $box->alignPivot('bottom', 3, 3);
        $this->assertEquals(320, $box->getPivot()->getX());
        $this->assertEquals(477, $box->getPivot()->getY());

        $result = $box->alignPivot('bottom-right', 3, 3);
        $this->assertEquals(637, $box->getPivot()->getX());
        $this->assertEquals(477, $box->getPivot()->getY());

        $this->assertInstanceOf(Size::class, $result);
    }

    public function testAlignPivotTo(): void
    {
        $container = new Size(800, 600);
        $size = new Size(200, 100);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(300, $size->getPivot()->getX());
        $this->assertEquals(250, $size->getPivot()->getY());

        $container = new Size(800, 600);
        $size = new Size(100, 100);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(350, $size->getPivot()->getX());
        $this->assertEquals(250, $size->getPivot()->getY());

        $container = new Size(800, 600);
        $size = new Size(800, 600);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(0, $size->getPivot()->getX());
        $this->assertEquals(0, $size->getPivot()->getY());

        $container = new Size(100, 100);
        $size = new Size(800, 600);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(-350, $size->getPivot()->getX());
        $this->assertEquals(-250, $size->getPivot()->getY());

        $container = new Size(100, 100);
        $size = new Size(800, 600);
        $size->alignPivotTo($container, 'bottom-right');
        $this->assertEquals(-700, $size->getPivot()->getX());
        $this->assertEquals(-500, $size->getPivot()->getY());
    }

    public function testgetRelativePositionTo(): void
    {
        $container = new Size(800, 600);
        $input = new Size(200, 100);
        $container->alignPivot('top-left');
        $input->alignPivot('top-left');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(0, $pos->getX());
        $this->assertEquals(0, $pos->getY());

        $container = new Size(800, 600);
        $input = new Size(200, 100);
        $container->alignPivot('center');
        $input->alignPivot('top-left');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(400, $pos->getX());
        $this->assertEquals(300, $pos->getY());

        $container = new Size(800, 600);
        $input = new Size(200, 100);
        $container->alignPivot('bottom-right');
        $input->alignPivot('top-right');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(600, $pos->getX());
        $this->assertEquals(600, $pos->getY());

        $container = new Size(800, 600);
        $input = new Size(200, 100);
        $container->alignPivot('center');
        $input->alignPivot('center');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(300, $pos->getX());
        $this->assertEquals(250, $pos->getY());

        $container = new Size(100, 200);
        $input = new Size(100, 100);
        $container->alignPivot('center');
        $input->alignPivot('center');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(0, $pos->getX());
        $this->assertEquals(50, $pos->getY());
    }

    public function testToPolygon(): void
    {
        $size = new Size(300, 200);
        $poly = $size->toPolygon();
        $this->assertInstanceOf(Polygon::class, $poly);
        $this->assertCount(4, $poly);
        $this->assertEquals(0, $poly[0]->getX());
        $this->assertEquals(0, $poly[0]->getY());
        $this->assertEquals(300, $poly[1]->getX());
        $this->assertEquals(0, $poly[1]->getY());
        $this->assertEquals(300, $poly[2]->getX());
        $this->assertEquals(-200, $poly[2]->getY());
        $this->assertEquals(0, $poly[3]->getX());
        $this->assertEquals(-200, $poly[3]->getY());
    }

    public function testResize(): void
    {
        $size = new Size(300, 200);
        $result = $size->resize(120, 150);
        $this->assertInstanceOf(Size::class, $result);

        $size = new Size(300, 200);
        $result = $size->resize(height: 100);
        $this->assertInstanceOf(Size::class, $result);
    }

    public function testResizeDown(): void
    {
        $size = new Size(300, 200);
        $result = $size->resizeDown(120, 150);
        $this->assertInstanceOf(Size::class, $result);

        $size = new Size(300, 200);
        $result = $size->resizeDown(height: 100);
        $this->assertInstanceOf(Size::class, $result);
    }

    public function testScale(): void
    {
        $size = new Size(300, 200);
        $result = $size->scale(120, 150);
        $this->assertInstanceOf(Size::class, $result);

        $size = new Size(300, 200);
        $result = $size->scale(height: 100);
        $this->assertInstanceOf(Size::class, $result);
    }

    public function testScaleDown(): void
    {
        $size = new Size(300, 200);
        $result = $size->scaleDown(120, 150);
        $this->assertInstanceOf(Size::class, $result);

        $size = new Size(300, 200);
        $result = $size->scaleDown(height: 100);
        $this->assertInstanceOf(Size::class, $result);
    }

    public function testCover(): void
    {
        $size = new Size(300, 200);
        $result = $size->cover(120, 150);
        $this->assertInstanceOf(Size::class, $result);
    }

    public function testContain(): void
    {
        $size = new Size(100, 100);
        $result = $size->contain(800, 600);
        $this->assertInstanceOf(Size::class, $result);
        $this->assertEquals(600, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());
    }
}
