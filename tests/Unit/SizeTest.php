<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Size::class)]
final class SizeTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $size = new Size(100, 200);
        $this->assertInstanceOf(Size::class, $size);
        $this->assertEquals(100, $size->width());
        $this->assertEquals(200, $size->height());
    }

    public function testConstructorWithPivot(): void
    {
        $size = new Size(100, 200, new Point(10, 20));
        $this->assertEquals(100, $size->width());
        $this->assertEquals(200, $size->height());
        $this->assertEquals(10, $size->pivot()->x());
        $this->assertEquals(20, $size->pivot()->y());
    }

    public function testConstructorNegativeWidth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Size(-1, 100);
    }

    public function testConstructorNegativeHeight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Size(100, -1);
    }

    public function testSetPosition(): void
    {
        $size = new Size(100, 200);
        $result = $size->setPosition(new Point(50, 60));
        $this->assertInstanceOf(Size::class, $result);
    }

    public function testMovePivotTopLeft(): void
    {
        $size = new Size(100, 200);
        $result = $size->movePivot(Alignment::TOP_LEFT, 5, 10);
        $this->assertInstanceOf(Size::class, $result);
        $this->assertEquals(5, $size->pivot()->x());
        $this->assertEquals(10, $size->pivot()->y());
    }

    public function testMovePivotTop(): void
    {
        $size = new Size(100, 200);
        $size->movePivot(Alignment::TOP, 3, 7);
        $this->assertEquals(53, $size->pivot()->x());
        $this->assertEquals(7, $size->pivot()->y());
    }

    public function testMovePivotTopRight(): void
    {
        $size = new Size(100, 200);
        $size->movePivot(Alignment::TOP_RIGHT, 5, 10);
        $this->assertEquals(95, $size->pivot()->x());
        $this->assertEquals(10, $size->pivot()->y());
    }

    public function testMovePivotLeft(): void
    {
        $size = new Size(100, 200);
        $size->movePivot(Alignment::LEFT, 5, 10);
        $this->assertEquals(5, $size->pivot()->x());
        $this->assertEquals(110, $size->pivot()->y());
    }

    public function testMovePivotCenter(): void
    {
        $size = new Size(100, 200);
        $size->movePivot(Alignment::CENTER);
        $this->assertEquals(50, $size->pivot()->x());
        $this->assertEquals(100, $size->pivot()->y());
    }

    public function testMovePivotRight(): void
    {
        $size = new Size(100, 200);
        $size->movePivot(Alignment::RIGHT, 5, 10);
        $this->assertEquals(95, $size->pivot()->x());
        $this->assertEquals(110, $size->pivot()->y());
    }

    public function testMovePivotBottomLeft(): void
    {
        $size = new Size(100, 200);
        $size->movePivot(Alignment::BOTTOM_LEFT, 5, 10);
        $this->assertEquals(5, $size->pivot()->x());
        $this->assertEquals(190, $size->pivot()->y());
    }

    public function testMovePivotBottom(): void
    {
        $size = new Size(100, 200);
        $size->movePivot(Alignment::BOTTOM, 3, 7);
        $this->assertEquals(53, $size->pivot()->x());
        $this->assertEquals(193, $size->pivot()->y());
    }

    public function testMovePivotBottomRight(): void
    {
        $size = new Size(100, 200);
        $size->movePivot(Alignment::BOTTOM_RIGHT, 5, 10);
        $this->assertEquals(95, $size->pivot()->x());
        $this->assertEquals(190, $size->pivot()->y());
    }

    public function testGetIterator(): void
    {
        $size = new Size(100, 200);
        $values = iterator_to_array($size->getIterator());
        $this->assertEquals([100, 200], $values);
    }

    public function testDebugInfo(): void
    {
        $size = new Size(100, 200);
        $info = $size->__debugInfo();
        $this->assertArrayHasKey('width', $info);
        $this->assertArrayHasKey('height', $info);
        $this->assertArrayHasKey('pivot', $info);
        $this->assertEquals(100, $info['width']);
        $this->assertEquals(200, $info['height']);
    }

    public function testResizeInvalidArguments(): void
    {
        $size = new Size(100, 200);
        $this->expectException(InvalidArgumentException::class);
        $size->resize(0, 0);
    }

    public function testResizeDownInvalidArguments(): void
    {
        $size = new Size(100, 200);
        $this->expectException(InvalidArgumentException::class);
        $size->resizeDown(0, 0);
    }

    public function testScaleInvalidArguments(): void
    {
        $size = new Size(100, 200);
        $this->expectException(InvalidArgumentException::class);
        $size->scale(0, 0);
    }

    public function testScaleDownInvalidArguments(): void
    {
        $size = new Size(100, 200);
        $this->expectException(InvalidArgumentException::class);
        $size->scaleDown(0, 0);
    }

    public function testCoverInvalidArguments(): void
    {
        $size = new Size(100, 200);
        $this->expectException(InvalidArgumentException::class);
        $size->cover(0, 0);
    }

    public function testContainInvalidArguments(): void
    {
        $size = new Size(100, 200);
        $this->expectException(InvalidArgumentException::class);
        $size->contain(0, 0);
    }

    public function testContainDownInvalidArguments(): void
    {
        $size = new Size(100, 200);
        $this->expectException(InvalidArgumentException::class);
        $size->containDown(0, 0);
    }
}
