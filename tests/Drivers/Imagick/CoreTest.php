<?php

namespace Intervention\Image\Tests\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Tests\TestCase;

class CoreTest extends TestCase
{
    protected Core $core;

    public function setUp(): void
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

        $this->core = new Core($imagick);
    }

    public function testAdd(): void
    {
        $imagick = new Imagick();
        $imagick->newImage(100, 100, new ImagickPixel('red'));
        $this->assertEquals(3, $this->core->count());
        $result = $this->core->add(new Frame($imagick));
        $this->assertEquals(4, $this->core->count());
        $this->assertInstanceOf(Core::class, $result);
    }

    public function testCount(): void
    {
        $this->assertEquals(3, $this->core->count());
    }

    public function testIterator(): void
    {
        foreach ($this->core as $frame) {
            $this->assertInstanceOf(Frame::class, $frame);
        }
    }

    public function testNative(): void
    {
        $this->assertInstanceOf(Imagick::class, $this->core->native());
    }

    public function testSetGetLoops(): void
    {
        $this->assertEquals(0, $this->core->loops());
        $result = $this->core->setLoops(12);
        $this->assertEquals(12, $this->core->loops());
        $this->assertInstanceOf(Core::class, $result);
    }

    public function testHas(): void
    {
        $this->assertTrue($this->core->has(0));
        $this->assertTrue($this->core->has(1));
        $this->assertTrue($this->core->has(2));
        $this->assertFalse($this->core->has(3));
    }

    public function testPush(): void
    {
        $im = new Imagick();
        $im->newImage(100, 100, new ImagickPixel('green'));
        $this->assertEquals(3, $this->core->count());
        $result = $this->core->push(new Frame($im));
        $this->assertEquals(4, $this->core->count());
        $this->assertEquals(4, $result->count());
    }

    public function testGet(): void
    {
        $this->assertInstanceOf(Frame::class, $this->core->get(0));
        $this->assertInstanceOf(Frame::class, $this->core->get(1));
        $this->assertInstanceOf(Frame::class, $this->core->get(2));
        $this->assertNull($this->core->get(3));
        $this->assertEquals('foo', $this->core->get(3, 'foo'));
    }

    public function testEmpty(): void
    {
        $result = $this->core->empty();
        $this->assertEquals(0, $this->core->count());
        $this->assertEquals(0, $result->count());
    }

    public function testSlice(): void
    {
        $this->assertEquals(3, $this->core->count());
        $result = $this->core->slice(1, 2);
        $this->assertEquals(2, $this->core->count());
        $this->assertEquals(2, $result->count());
    }

    public function testFirst(): void
    {
        $this->assertInstanceOf(Frame::class, $this->core->first());
    }

    public function testLast(): void
    {
        $this->assertInstanceOf(Frame::class, $this->core->last());
    }
}
