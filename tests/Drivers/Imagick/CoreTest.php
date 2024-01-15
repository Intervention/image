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

    public function testAdd(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $core = new Core($imagick);
        $this->assertEquals(1, $core->count());
        $result = $core->add(new Frame(clone $imagick));
        $this->assertEquals(2, $core->count());
        $this->assertInstanceOf(Core::class, $result);
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

    public function testHas(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $imagick->addImage(clone $imagick);
        $core = new Core($imagick);
        $this->assertTrue($core->has(0));
        $this->assertTrue($core->has(1));
        $this->assertFalse($core->has(2));
    }

    public function testPush(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $imagick->addImage(clone $imagick);
        $core = new Core($imagick);
        $this->assertEquals(2, $core->count());

        $im = new Imagick();
        $im->newImage(100, 100, new ImagickPixel('green'));
        $result = $core->push(new Frame($im));
        $this->assertEquals(3, $core->count());
        $this->assertEquals(3, $result->count());
    }

    public function testGet(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $imagick->addImage(clone $imagick);
        $core = new Core($imagick);
        $this->assertInstanceOf(Frame::class, $core->get(0));
        $this->assertInstanceOf(Frame::class, $core->get(1));
        $this->assertNull($core->get(2));
        $this->assertEquals('foo', $core->get(2, 'foo'));
    }

    public function testEmpty(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $imagick->addImage(clone $imagick);
        $core = new Core($imagick);
        $this->assertEquals(2, $core->count());
        $result = $core->empty();
        $this->assertEquals(0, $core->count());
        $this->assertEquals(0, $result->count());
    }

    public function testSlice(): void
    {
        $imagick = new Imagick();

        $im = new Imagick();
        $im->newImage(10, 10, new ImagickPixel('red'));
        $imagick->addImage($im);

        $im = new Imagick();
        $im->newImage(10, 10, new ImagickPixel('green'));
        $imagick->addImage($im);

        $im = new Imagick();
        $im->newImage(10, 10, new ImagickPixel('blue'));
        $imagick->addImage($im);

        $core = new Core($imagick);
        $this->assertEquals(3, $core->count());
        $result = $core->slice(1, 2);
        $this->assertEquals(2, $core->count());
        $this->assertEquals(2, $result->count());
    }

    public function testLast(): void
    {
        $imagick = new Imagick();

        $im = new Imagick();
        $im->newImage(10, 10, new ImagickPixel('red'));
        $imagick->addImage($im);

        $im = new Imagick();
        $im->newImage(10, 10, new ImagickPixel('green'));
        $imagick->addImage($im);

        $im = new Imagick();
        $im->newImage(10, 10, new ImagickPixel('blue'));
        $imagick->addImage($im);

        $core = new Core($imagick);
        $result = $core->last();
        $this->assertInstanceOf(Frame::class, $result);
    }
}
