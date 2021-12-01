<?php

namespace Intervention\Image\Tests\Geometry;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Geometry\Size;
use PHPUnit\Framework\TestCase;

class ResizerTest extends TestCase
{
    public function testMake(): void
    {
        $result = Resizer::make();
        $this->assertInstanceOf(Resizer::class, $result);
    }

    public function testSetTargetWidth(): void
    {
        $resizer = new Resizer();
        $result = $resizer->width(100);
        $this->assertInstanceOf(Resizer::class, $result);
        $result = $resizer->toWidth(100);
        $this->assertInstanceOf(Resizer::class, $result);
    }

    public function testSetTargetHeight(): void
    {
        $resizer = new Resizer();
        $result = $resizer->height(100);
        $this->assertInstanceOf(Resizer::class, $result);
        $result = $resizer->toHeight(100);
        $this->assertInstanceOf(Resizer::class, $result);
    }

    public function testSetTargetSizeByArray()
    {
        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer = $resizer->setTargetSizeByArray([800, 600]);
        $this->assertInstanceOf(Resizer::class, $resizer);
        $this->assertEquals(800, $resizer->resize($size)->getWidth());
        $this->assertEquals(600, $resizer->resize($size)->getHeight());

        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer = $resizer->setTargetSizeByArray([800]);
        $this->assertInstanceOf(Resizer::class, $resizer);
        $this->assertEquals(800, $resizer->resize($size)->getWidth());
        $this->assertEquals(200, $resizer->resize($size)->getHeight());

        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer = $resizer->setTargetSizeByArray([function ($size) {
            $size->width(80);
            $size->height(40);
        }]);
        $this->assertInstanceOf(Resizer::class, $resizer);
        $this->assertEquals(80, $resizer->resize($size)->getWidth());
        $this->assertEquals(40, $resizer->resize($size)->getHeight());

        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer = $resizer->setTargetSizeByArray([function ($size) {
            $size->width(80);
        }]);
        $this->assertInstanceOf(Resizer::class, $resizer);
        $this->assertEquals(80, $resizer->resize($size)->getWidth());
        $this->assertEquals(200, $resizer->resize($size)->getHeight());

        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer = $resizer->setTargetSizeByArray([function ($size) {
            $size->height(10);
        }]);
        $this->assertInstanceOf(Resizer::class, $resizer);
        $this->assertEquals(300, $resizer->resize($size)->getWidth());
        $this->assertEquals(10, $resizer->resize($size)->getHeight());
    }

    public function testSetTargetSize(): void
    {
        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer = $resizer->setTargetSize(new Size(200, 100));
        $this->assertInstanceOf(Resizer::class, $resizer);
        $this->assertEquals(200, $resizer->resize($size)->getWidth());
        $this->assertEquals(100, $resizer->resize($size)->getHeight());
    }

    public function testToSize(): void
    {
        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer = $resizer->toSize(new Size(200, 100));
        $this->assertInstanceOf(Resizer::class, $resizer);
        $this->assertEquals(200, $resizer->resize($size)->getWidth());
        $this->assertEquals(100, $resizer->resize($size)->getHeight());
    }

    public function testResize()
    {
        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer->width(150);
        $result = $resizer->resize($size);
        $this->assertEquals(150, $result->getWidth());
        $this->assertEquals(200, $result->getHeight());

        $size = new Size(300, 200);
        $resizer = new Resizer();
        $resizer->width(20);
        $resizer->height(10);
        $result = $resizer->resize($size);
        $this->assertEquals(20, $result->getWidth());
        $this->assertEquals(10, $result->getHeight());
    }

    public function testResizeDown()
    {
        // 800x600 > 1000x2000 = 800x600
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $resizer->height(2000);
        $result = $resizer->resizeDown($size);
        $this->assertEquals(800, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());

        // 800x600 > 400x1000 = 400x600
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(400);
        $resizer->height(1000);
        $result = $resizer->resizeDown($size);
        $this->assertEquals(400, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());

        // 800x600 > 1000x400 = 800x400
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $resizer->height(400);
        $result = $resizer->resizeDown($size);
        $this->assertEquals(800, $result->getWidth());
        $this->assertEquals(400, $result->getHeight());

        // 800x600 > 400x300 = 400x300
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(400);
        $resizer->height(300);
        $result = $resizer->resizeDown($size);
        $this->assertEquals(400, $result->getWidth());
        $this->assertEquals(300, $result->getHeight());

        // 800x600 > 1000xnull = 800x600
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $result = $resizer->resizeDown($size);
        $this->assertEquals(800, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());

        // 800x600 > nullx1000 = 800x600
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->height(1000);
        $result = $resizer->resizeDown($size);
        $this->assertEquals(800, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());
    }

    public function testScale()
    {
        // 800x600 > 1000x2000 = 1000x750
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $resizer->height(2000);
        $result = $resizer->scale($size);
        $this->assertEquals(1000, $result->getWidth());
        $this->assertEquals(750, $result->getHeight());

        // 800x600 > 2000x1000 = 1333x1000
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(2000);
        $resizer->height(1000);
        $result = $resizer->scale($size);
        $this->assertEquals(1333, $result->getWidth());
        $this->assertEquals(1000, $result->getHeight());

        // // 800x600 > nullx3000 = 4000x3000
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->height(3000);
        $result = $resizer->scale($size);
        $this->assertEquals(4000, $result->getWidth());
        $this->assertEquals(3000, $result->getHeight());

        // // 800x600 > 8000xnull = 8000x6000
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(8000);
        $result = $resizer->scale($size);
        $this->assertEquals(8000, $result->getWidth());
        $this->assertEquals(6000, $result->getHeight());

        // // 800x600 > 100x400 = 100x75
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(100);
        $resizer->height(400);
        $result = $resizer->scale($size);
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(75, $result->getHeight());

        // // 800x600 > 400x100 = 133x100
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(400);
        $resizer->height(100);
        $result = $resizer->scale($size);
        $this->assertEquals(133, $result->getWidth());
        $this->assertEquals(100, $result->getHeight());

        // // 800x600 > nullx300 = 400x300
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->height(300);
        $result = $resizer->scale($size);
        $this->assertEquals(400, $result->getWidth());
        $this->assertEquals(300, $result->getHeight());

        // // 800x600 > 80xnull = 80x60
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(80);
        $result = $resizer->scale($size);
        $this->assertEquals(80, $result->getWidth());
        $this->assertEquals(60, $result->getHeight());

        // // 640x480 > 225xnull = 225x169
        $size = new Size(640, 480);
        $resizer = new Resizer();
        $resizer->width(225);
        $result = $resizer->scale($size);
        $this->assertEquals(225, $result->getWidth());
        $this->assertEquals(169, $result->getHeight());

        // // 640x480 > 223xnull = 223x167
        $size = new Size(640, 480);
        $resizer = new Resizer();
        $resizer->width(223);
        $result = $resizer->scale($size);
        $this->assertEquals(223, $result->getWidth());
        $this->assertEquals(167, $result->getHeight());

        // // 600x800 > 300x300 = 225x300
        $size = new Size(600, 800);
        $resizer = new Resizer();
        $resizer->width(300);
        $resizer->height(300);
        $result = $resizer->scale($size);
        $this->assertEquals(225, $result->getWidth());
        $this->assertEquals(300, $result->getHeight());

        // // 800x600 > 400x10 = 13x10
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(400);
        $resizer->height(10);
        $result = $resizer->scale($size);
        $this->assertEquals(13, $result->getWidth());
        $this->assertEquals(10, $result->getHeight());

        // // 800x600 > 1000x1200 = 1000x750
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $resizer->height(1200);
        $result = $resizer->scale($size);
        $this->assertEquals(1000, $result->getWidth());
        $this->assertEquals(750, $result->getHeight());

        $size = new Size(12000, 12);
        $resizer = new Resizer();
        $resizer->width(4000);
        $resizer->height(3000);
        $result = $resizer->scale($size);
        $this->assertEquals(4000, $result->getWidth());
        $this->assertEquals(4, $result->getHeight());

        $size = new Size(12, 12000);
        $resizer = new Resizer();
        $resizer->width(4000);
        $resizer->height(3000);
        $result = $resizer->scale($size);
        $this->assertEquals(3, $result->getWidth());
        $this->assertEquals(3000, $result->getHeight());

        $size = new Size(12000, 6000);
        $resizer = new Resizer();
        $resizer->width(4000);
        $resizer->height(3000);
        $result = $resizer->scale($size);
        $this->assertEquals(4000, $result->getWidth());
        $this->assertEquals(2000, $result->getHeight());
    }

    public function testScaleDown()
    {
        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $resizer->height(2000);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(800, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $resizer->height(600);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(800, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $resizer->height(300);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(400, $result->getWidth());
        $this->assertEquals(300, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(400);
        $resizer->height(1000);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(400, $result->getWidth());
        $this->assertEquals(300, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(400);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(400, $result->getWidth());
        $this->assertEquals(300, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->height(300);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(400, $result->getWidth());
        $this->assertEquals(300, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(1000);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(800, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->height(1000);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(800, $result->getWidth());
        $this->assertEquals(600, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(100);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(75, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(300);
        $resizer->height(200);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(267, $result->getWidth());
        $this->assertEquals(200, $result->getHeight());

        $size = new Size(600, 800);
        $resizer = new Resizer();
        $resizer->width(300);
        $resizer->height(300);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(225, $result->getWidth());
        $this->assertEquals(300, $result->getHeight());

        $size = new Size(800, 600);
        $resizer = new Resizer();
        $resizer->width(400);
        $resizer->height(10);
        $result = $resizer->scaleDown($size);
        $this->assertEquals(13, $result->getWidth());
        $this->assertEquals(10, $result->getHeight());
    }

    /**
     * @dataProvider coverDataProvider
    */
    public function testCover($origin, $target, $result): void
    {
        $resizer = new Resizer();
        $resizer->toSize($target);
        $resized = $resizer->cover($origin);
        $this->assertEquals($result->getWidth(), $resized->getWidth());
        $this->assertEquals($result->getHeight(), $resized->getHeight());
    }

    public function coverDataProvider(): array
    {
        return [
            [new Size(800, 600), new Size(100, 100), new Size(133, 100)],
            [new Size(800, 600), new Size(200, 100), new Size(200, 150)],
            [new Size(800, 600), new Size(100, 200), new Size(267, 200)],
            [new Size(800, 600), new Size(2000, 10), new Size(2000, 1500)],
            [new Size(800, 600), new Size(10, 2000), new Size(2667, 2000)],
            [new Size(800, 600), new Size(800, 600), new Size(800, 600)],
            [new Size(400, 300), new Size(120, 120), new Size(160, 120)],
            [new Size(600, 800), new Size(100, 100), new Size(100, 133)],
            [new Size(100, 100), new Size(800, 600), new Size(800, 800)],
        ];
    }

    /**
     * @dataProvider containDataProvider
    */
    public function testContain($origin, $target, $result): void
    {
        $resizer = new Resizer();
        $resizer->toSize($target);
        $resized = $resizer->contain($origin);
        $this->assertEquals($result->getWidth(), $resized->getWidth());
        $this->assertEquals($result->getHeight(), $resized->getHeight());
    }

    public function containDataProvider(): array
    {
        return [
            [new Size(800, 600), new Size(100, 100), new Size(100, 75)],
            [new Size(800, 600), new Size(200, 100), new Size(133, 100)],
            [new Size(800, 600), new Size(100, 200), new Size(100, 75)],
            [new Size(800, 600), new Size(2000, 10), new Size(13, 10)],
            [new Size(800, 600), new Size(10, 2000), new Size(10, 8)],
            [new Size(800, 600), new Size(800, 600), new Size(800, 600)],
            [new Size(400, 300), new Size(120, 120), new Size(120, 90)],
            [new Size(600, 800), new Size(100, 100), new Size(75, 100)],
            [new Size(100, 100), new Size(800, 600), new Size(600, 600)],
        ];
    }

    /**
     * @dataProvider cropDataProvider
    */
    public function testCrop($origin, $target, $position, $result): void
    {
        $resizer = new Resizer();
        $resizer->toSize($target);
        $resized = $resizer->crop($origin, $position);
        $this->assertEquals($result->getWidth(), $resized->getWidth());
        $this->assertEquals($result->getHeight(), $resized->getHeight());
        $this->assertEquals($result->getPivot()->getX(), $resized->getPivot()->getX());
        $this->assertEquals($result->getPivot()->getY(), $resized->getPivot()->getY());
    }

    public function cropDataProvider(): array
    {
        return [
            [new Size(800, 600), new Size(100, 100), 'center', new Size(100, 100, new Point(350, 250))],
            [new Size(800, 600), new Size(200, 100), 'center', new Size(200, 100, new Point(300, 250))],
            [new Size(800, 600), new Size(100, 200), 'center', new Size(100, 200, new Point(350, 200))],
            [new Size(800, 600), new Size(2000, 10), 'center', new Size(2000, 10, new Point(-600, 295))],
            [new Size(800, 600), new Size(10, 2000), 'center', new Size(10, 2000, new Point(395, -700))],
            [new Size(800, 600), new Size(800, 600), 'center', new Size(800, 600, new Point(0, 0))],
            [new Size(400, 300), new Size(120, 120), 'center', new Size(120, 120, new Point(140, 90))],
            [new Size(600, 800), new Size(100, 100), 'center', new Size(100, 100, new Point(250, 350))],
        ];
    }
}
