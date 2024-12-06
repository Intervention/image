<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(Core::class)]
final class CoreTest extends BaseTestCase
{
    protected Core $core;

    protected function setUp(): void
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

    public function testSetNative(): void
    {
        $imagick1 = new Imagick();
        $imagick1->newImage(10, 10, new ImagickPixel('red'));

        $imagick2 = new Imagick();
        $imagick2->newImage(10, 10, new ImagickPixel('red'));

        $core = new Core($imagick1);
        $this->assertEquals($imagick1, $core->native());
        $core->setNative($imagick2);
        $this->assertEquals($imagick2, $core->native());
    }

    public function testFrame(): void
    {
        $this->assertInstanceOf(Frame::class, $this->core->frame(0));
        $this->assertInstanceOf(Frame::class, $this->core->frame(1));
        $this->assertInstanceOf(Frame::class, $this->core->frame(2));
        $this->expectException(AnimationException::class);
        $this->core->frame(3);
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
