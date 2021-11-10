<?php

namespace Intervention\Image\Tests\Geometry;

use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Geometry\Size;
use PHPUnit\Framework\TestCase;

class ResizerTest extends TestCase
{
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
}
