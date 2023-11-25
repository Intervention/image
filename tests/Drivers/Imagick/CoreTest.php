<?php

namespace Intervention\Image\Tests\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Tests\TestCase;

class CoreTest extends TestCase
{
    public function testConstructor(): void
    {
        $imagick = new Imagick();
        $core = new Core($imagick);
        $this->assertInstanceOf(Core::class, $core);
    }

    public function testCount(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $imagick->addImage(clone $imagick);
        $core = new Core($imagick);
        $this->assertEquals(2, $core->count());
    }

    public function testIterator(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $imagick->addImage(clone $imagick);
        $core = new Core($imagick);
        foreach ($core as $frame) {
            $this->assertInstanceOf(Frame::class, $frame);
        }
    }

    public function testNative(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $core = new Core($imagick);
        $this->assertInstanceOf(Imagick::class, $core->native());
    }

    public function testSetGetLoops(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $core = new Core($imagick);
        $this->assertEquals(0, $core->loops());
        $result = $core->setLoops(12);
        $this->assertEquals(12, $core->loops());
        $this->assertInstanceOf(Core::class, $result);
    }
}
