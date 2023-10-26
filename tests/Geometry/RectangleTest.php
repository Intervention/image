<?php

namespace Intervention\Image\Tests\Geometry;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\TestCase;

class RectangleTest extends TestCase
{
    public function testConstructor(): void
    {
        $rectangle = new Rectangle(300, 200);
        $this->assertEquals(0, $rectangle[0]->getX());
        $this->assertEquals(0, $rectangle[0]->getY());
        $this->assertEquals(300, $rectangle[1]->getX());
        $this->assertEquals(0, $rectangle[1]->getY());
        $this->assertEquals(300, $rectangle[2]->getX());
        $this->assertEquals(-200, $rectangle[2]->getY());
        $this->assertEquals(0, $rectangle[3]->getX());
        $this->assertEquals(-200, $rectangle[3]->getY());
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

    public function testGetAspectRatio()
    {
        $size = new Rectangle(800, 600);
        $this->assertEquals(1.333, round($size->aspectRatio(), 3));

        $size = new Rectangle(100, 100);
        $this->assertEquals(1, $size->aspectRatio());

        $size = new Rectangle(1920, 1080);
        $this->assertEquals(1.778, round($size->aspectRatio(), 3));
    }

    public function testFitsInto()
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

    public function testIsLandscape()
    {
        $box = new Rectangle(100, 100);
        $this->assertFalse($box->isLandscape());

        $box = new Rectangle(100, 200);
        $this->assertFalse($box->isLandscape());

        $box = new Rectangle(300, 200);
        $this->assertTrue($box->isLandscape());
    }

    public function testIsPortrait()
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
        $this->assertEquals(0, $pivot->getX());
        $result = $box->setPivot(new Point(10, 0));
        $this->assertInstanceOf(Rectangle::class, $result);
        $this->assertEquals(10, $box->pivot()->getX());
    }

    public function testAlignPivot(): void
    {
        $box = new Rectangle(640, 480);
        $this->assertEquals(0, $box->pivot()->getX());
        $this->assertEquals(0, $box->pivot()->getY());

        $box->movePivot('top-left', 3, 3);
        $this->assertEquals(3, $box->pivot()->getX());
        $this->assertEquals(3, $box->pivot()->getY());

        $box->movePivot('top', 3, 3);
        $this->assertEquals(323, $box->pivot()->getX());
        $this->assertEquals(3, $box->pivot()->getY());

        $box->movePivot('top-right', 3, 3);
        $this->assertEquals(637, $box->pivot()->getX());
        $this->assertEquals(3, $box->pivot()->getY());

        $box->movePivot('left', 3, 3);
        $this->assertEquals(3, $box->pivot()->getX());
        $this->assertEquals(243, $box->pivot()->getY());

        $box->movePivot('center', 3, 3);
        $this->assertEquals(323, $box->pivot()->getX());
        $this->assertEquals(243, $box->pivot()->getY());

        $box->movePivot('right', 3, 3);
        $this->assertEquals(637, $box->pivot()->getX());
        $this->assertEquals(243, $box->pivot()->getY());

        $box->movePivot('bottom-left', 3, 3);
        $this->assertEquals(3, $box->pivot()->getX());
        $this->assertEquals(477, $box->pivot()->getY());

        $box->movePivot('bottom', 3, 3);
        $this->assertEquals(323, $box->pivot()->getX());
        $this->assertEquals(477, $box->pivot()->getY());

        $result = $box->movePivot('bottom-right', 3, 3);
        $this->assertEquals(637, $box->pivot()->getX());
        $this->assertEquals(477, $box->pivot()->getY());

        $this->assertInstanceOf(Rectangle::class, $result);
    }

    public function testAlignPivotTo(): void
    {
        $container = new Rectangle(800, 600);
        $size = new Rectangle(200, 100);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(300, $size->pivot()->getX());
        $this->assertEquals(250, $size->pivot()->getY());

        $container = new Rectangle(800, 600);
        $size = new Rectangle(100, 100);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(350, $size->pivot()->getX());
        $this->assertEquals(250, $size->pivot()->getY());

        $container = new Rectangle(800, 600);
        $size = new Rectangle(800, 600);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(0, $size->pivot()->getX());
        $this->assertEquals(0, $size->pivot()->getY());

        $container = new Rectangle(100, 100);
        $size = new Rectangle(800, 600);
        $size->alignPivotTo($container, 'center');
        $this->assertEquals(-350, $size->pivot()->getX());
        $this->assertEquals(-250, $size->pivot()->getY());

        $container = new Rectangle(100, 100);
        $size = new Rectangle(800, 600);
        $size->alignPivotTo($container, 'bottom-right');
        $this->assertEquals(-700, $size->pivot()->getX());
        $this->assertEquals(-500, $size->pivot()->getY());
    }

    public function testgetRelativePositionTo(): void
    {
        $container = new Rectangle(800, 600);
        $input = new Rectangle(200, 100);
        $container->movePivot('top-left');
        $input->movePivot('top-left');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(0, $pos->getX());
        $this->assertEquals(0, $pos->getY());

        $container = new Rectangle(800, 600);
        $input = new Rectangle(200, 100);
        $container->movePivot('center');
        $input->movePivot('top-left');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(400, $pos->getX());
        $this->assertEquals(300, $pos->getY());

        $container = new Rectangle(800, 600);
        $input = new Rectangle(200, 100);
        $container->movePivot('bottom-right');
        $input->movePivot('top-right');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(600, $pos->getX());
        $this->assertEquals(600, $pos->getY());

        $container = new Rectangle(800, 600);
        $input = new Rectangle(200, 100);
        $container->movePivot('center');
        $input->movePivot('center');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(300, $pos->getX());
        $this->assertEquals(250, $pos->getY());

        $container = new Rectangle(100, 200);
        $input = new Rectangle(100, 100);
        $container->movePivot('center');
        $input->movePivot('center');
        $pos = $container->getRelativePositionTo($input);
        $this->assertEquals(0, $pos->getX());
        $this->assertEquals(50, $pos->getY());
    }
}
