<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd;

use GdImage;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[CoversClass(Core::class)]
final class CoreTest extends BaseTestCase
{
    protected Core $core;

    protected function setUp(): void
    {
        $this->core = new Core([
            new Frame(imagecreatetruecolor(3, 2)),
            new Frame(imagecreatetruecolor(3, 2)),
            new Frame(imagecreatetruecolor(3, 2)),
        ]);
    }

    public function getTestFrame(): Frame
    {
        return new Frame(imagecreatetruecolor(3, 2));
    }

    public function testAdd(): void
    {
        $this->assertEquals(3, $this->core->count());
        $result = $this->core->add($this->getTestFrame());
        $this->assertEquals(4, $this->core->count());
        $this->assertInstanceOf(Core::class, $result);
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
        $this->assertInstanceOf(GdImage::class, $this->core->native());
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
        $this->assertInstanceOf(Core::class, $result);
        $this->assertEquals(12, $this->core->loops());
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
        $this->assertEquals(3, $this->core->count());
        $result = $this->core->push($this->getTestFrame());
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
