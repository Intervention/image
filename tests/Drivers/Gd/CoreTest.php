<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use GdImage;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Tests\TestCase;

class CoreTest extends TestCase
{
    public function testNative(): void
    {
        $core = new Core([
            new Frame(imagecreatetruecolor(3, 2))
        ]);
        $this->assertInstanceOf(GdImage::class, $core->native());
    }

    public function testSetNative(): void
    {
        $gd1 = imagecreatetruecolor(3, 2);
        $gd2 = imagecreatetruecolor(3, 2);
        $core = new Core([new Frame($gd1)]);
        $this->assertEquals($gd1, $core->native());
        $core->setNative($gd2);
        $this->assertEquals($gd2, $core->native());
    }

    public function testFrame(): void
    {
        $core = new Core([
            new Frame(imagecreatetruecolor(3, 2)),
            new Frame(imagecreatetruecolor(3, 2)),
        ]);
        $this->assertInstanceOf(Frame::class, $core->frame(0));
        $this->assertInstanceOf(Frame::class, $core->frame(1));
    }

    public function testSetGetLoops(): void
    {
        $core = new Core([
            new Frame(imagecreatetruecolor(3, 2))
        ]);

        $this->assertEquals(0, $core->loops());
        $result = $core->setLoops(12);
        $this->assertInstanceOf(Core::class, $result);
        $this->assertEquals(12, $core->loops());
    }
}
