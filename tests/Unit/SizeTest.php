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
        $size = new Size(800, 600);
        $this->assertInstanceOf(Size::class, $size);
        $this->assertEquals(800, $size->width());
        $this->assertEquals(600, $size->height());
    }

    public function testConstructorWithPivot(): void
    {
        $pivot = new Point(10, 20);
        $size = new Size(800, 600, $pivot);
        $this->assertEquals(800, $size->width());
        $this->assertEquals(600, $size->height());
        $this->assertEquals(10, $size->pivot()->x());
        $this->assertEquals(20, $size->pivot()->y());
    }

    public function testConstructorNegativeWidth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Size(-1, 600);
    }

    public function testConstructorNegativeHeight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Size(800, -1);
    }

    public function testConstructorZeroDimensions(): void
    {
        $size = new Size(0, 0);
        $this->assertEquals(0, $size->width());
        $this->assertEquals(0, $size->height());
    }

    public function testSetSize(): void
    {
        $size = new Size(800, 600);
        $result = $size->setSize(400, 300);
        $this->assertSame($size, $result);
        $this->assertEquals(400, $size->width());
        $this->assertEquals(300, $size->height());
    }

    public function testSetWidth(): void
    {
        $size = new Size(800, 600);
        $result = $size->setWidth(400);
        $this->assertSame($size, $result);
        $this->assertEquals(400, $size->width());
        $this->assertEquals(600, $size->height());
    }

    public function testSetHeight(): void
    {
        $size = new Size(800, 600);
        $result = $size->setHeight(300);
        $this->assertSame($size, $result);
        $this->assertEquals(800, $size->width());
        $this->assertEquals(300, $size->height());
    }

    public function testPivot(): void
    {
        $size = new Size(800, 600);
        $this->assertInstanceOf(Point::class, $size->pivot());
        $this->assertEquals(0, $size->pivot()->x());
        $this->assertEquals(0, $size->pivot()->y());
    }

    public function testSetPivot(): void
    {
        $size = new Size(800, 600);
        $pivot = new Point(100, 200);
        $result = $size->setPivot($pivot);
        $this->assertSame($size, $result);
        $this->assertSame($pivot, $size->pivot());
    }

    public function testSetPosition(): void
    {
        $size = new Size(800, 600);
        $position = new Point(50, 50);
        $result = $size->setPosition($position);
        $this->assertSame($size, $result);
    }

    public function testMovePivotTopLeft(): void
    {
        $size = new Size(800, 600);
        $result = $size->movePivot(Alignment::TOP_LEFT);
        $this->assertSame($size, $result);
        $this->assertEquals(0, $size->pivot()->x());
        $this->assertEquals(0, $size->pivot()->y());
    }

    public function testMovePivotTop(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::TOP);
        $this->assertEquals(400, $size->pivot()->x());
        $this->assertEquals(0, $size->pivot()->y());
    }

    public function testMovePivotTopRight(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::TOP_RIGHT);
        $this->assertEquals(800, $size->pivot()->x());
        $this->assertEquals(0, $size->pivot()->y());
    }

    public function testMovePivotLeft(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::LEFT);
        $this->assertEquals(0, $size->pivot()->x());
        $this->assertEquals(300, $size->pivot()->y());
    }

    public function testMovePivotCenter(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::CENTER);
        $this->assertEquals(400, $size->pivot()->x());
        $this->assertEquals(300, $size->pivot()->y());
    }

    public function testMovePivotRight(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::RIGHT);
        $this->assertEquals(800, $size->pivot()->x());
        $this->assertEquals(300, $size->pivot()->y());
    }

    public function testMovePivotBottomLeft(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::BOTTOM_LEFT);
        $this->assertEquals(0, $size->pivot()->x());
        $this->assertEquals(600, $size->pivot()->y());
    }

    public function testMovePivotBottom(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::BOTTOM);
        $this->assertEquals(400, $size->pivot()->x());
        $this->assertEquals(600, $size->pivot()->y());
    }

    public function testMovePivotBottomRight(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::BOTTOM_RIGHT);
        $this->assertEquals(800, $size->pivot()->x());
        $this->assertEquals(600, $size->pivot()->y());
    }

    public function testMovePivotWithOffsets(): void
    {
        $size = new Size(800, 600);
        $size->movePivot(Alignment::TOP_LEFT, 10, 20);
        $this->assertEquals(10, $size->pivot()->x());
        $this->assertEquals(20, $size->pivot()->y());
    }

    public function testMovePivotWithString(): void
    {
        $size = new Size(800, 600);
        $size->movePivot('center');
        $this->assertEquals(400, $size->pivot()->x());
        $this->assertEquals(300, $size->pivot()->y());
    }

    public function testMovePivotWithInvalidAlignmentFallsToDefault(): void
    {
        $size = new Size(800, 600);
        $size->movePivot('invalid-alignment', 5, 10);
        // default branch behaves like TOP_LEFT
        $this->assertEquals(5, $size->pivot()->x());
        $this->assertEquals(10, $size->pivot()->y());
    }

    public function testAlignPivotTo(): void
    {
        $size = new Size(200, 100);
        $reference = new Size(800, 600);
        $result = $size->alignPivotTo($reference, Alignment::CENTER);
        $this->assertSame($size, $result);
        $this->assertEquals(300, $size->pivot()->x());
        $this->assertEquals(250, $size->pivot()->y());
    }

    public function testAlignPivotToTopLeft(): void
    {
        $size = new Size(200, 100);
        $reference = new Size(800, 600);
        $size->alignPivotTo($reference, Alignment::TOP_LEFT);
        $this->assertEquals(0, $size->pivot()->x());
        $this->assertEquals(0, $size->pivot()->y());
    }

    public function testAlignPivotToBottomRight(): void
    {
        $size = new Size(200, 100);
        $reference = new Size(800, 600);
        $size->alignPivotTo($reference, Alignment::BOTTOM_RIGHT);
        $this->assertEquals(600, $size->pivot()->x());
        $this->assertEquals(500, $size->pivot()->y());
    }

    public function testRelativePositionTo(): void
    {
        $size1 = new Size(800, 600);
        $size1->movePivot(Alignment::CENTER);

        $size2 = new Size(200, 100);
        $size2->movePivot(Alignment::CENTER);

        $position = $size1->relativePositionTo($size2);
        $this->assertEquals(300, $position->x());
        $this->assertEquals(250, $position->y());
    }

    public function testAspectRatio(): void
    {
        $size = new Size(800, 600);
        $this->assertEqualsWithDelta(1.333, $size->aspectRatio(), 0.001);

        $size = new Size(600, 600);
        $this->assertEquals(1.0, $size->aspectRatio());

        $size = new Size(400, 800);
        $this->assertEquals(0.5, $size->aspectRatio());
    }

    public function testFitsInto(): void
    {
        $size = new Size(200, 100);
        $container = new Size(800, 600);
        $this->assertTrue($size->fitsInto($container));

        $size = new Size(800, 600);
        $container = new Size(800, 600);
        $this->assertTrue($size->fitsInto($container));

        $size = new Size(900, 600);
        $container = new Size(800, 600);
        $this->assertFalse($size->fitsInto($container));

        $size = new Size(800, 700);
        $container = new Size(800, 600);
        $this->assertFalse($size->fitsInto($container));
    }

    public function testIsLandscape(): void
    {
        $this->assertTrue((new Size(800, 600))->isLandscape());
        $this->assertFalse((new Size(600, 800))->isLandscape());
        $this->assertFalse((new Size(600, 600))->isLandscape());
    }

    public function testIsPortrait(): void
    {
        $this->assertTrue((new Size(600, 800))->isPortrait());
        $this->assertFalse((new Size(800, 600))->isPortrait());
        $this->assertFalse((new Size(600, 600))->isPortrait());
    }

    public function testResize(): void
    {
        $size = new Size(800, 600);
        $result = $size->resize(400, 300);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testResizeWidthOnly(): void
    {
        $size = new Size(800, 600);
        $result = $size->resize(400);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testResizeHeightOnly(): void
    {
        $size = new Size(800, 600);
        $result = $size->resize(height: 300);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testResizeDown(): void
    {
        $size = new Size(800, 600);
        $result = $size->resizeDown(400, 300);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testResizeDownDoesNotUpscale(): void
    {
        $size = new Size(400, 300);
        $result = $size->resizeDown(800, 600);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScale(): void
    {
        $size = new Size(800, 600);
        $result = $size->scale(400);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleByHeight(): void
    {
        $size = new Size(800, 600);
        $result = $size->scale(height: 300);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleDown(): void
    {
        $size = new Size(800, 600);
        $result = $size->scaleDown(400);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleDownDoesNotUpscale(): void
    {
        $size = new Size(400, 300);
        $result = $size->scaleDown(800);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testCover(): void
    {
        $size = new Size(800, 600);
        $result = $size->cover(400, 400);
        $this->assertEquals(533, $result->width());
        $this->assertEquals(400, $result->height());
    }

    public function testContain(): void
    {
        $size = new Size(800, 600);
        $result = $size->contain(400, 400);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testContainDown(): void
    {
        $size = new Size(800, 600);
        $result = $size->containDown(400, 400);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testContainDownDoesNotUpscale(): void
    {
        $size = new Size(200, 150);
        $result = $size->containDown(400, 400);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(150, $result->height());
    }

    public function testGetIterator(): void
    {
        $size = new Size(800, 600);
        $values = iterator_to_array($size->getIterator());
        $this->assertEquals([800, 600], $values);
    }

    public function testDebugInfo(): void
    {
        $size = new Size(800, 600);
        $info = $size->__debugInfo();
        $this->assertArrayHasKey('width', $info);
        $this->assertArrayHasKey('height', $info);
        $this->assertArrayHasKey('pivot', $info);
        $this->assertEquals(800, $info['width']);
        $this->assertEquals(600, $info['height']);
    }

    public function testResizeInvalid(): void
    {
        $size = new Size(800, 600);
        $this->expectException(InvalidArgumentException::class);
        $size->resize(-1, -1);
    }

    public function testResizeDownInvalid(): void
    {
        $size = new Size(800, 600);
        $this->expectException(InvalidArgumentException::class);
        $size->resizeDown(-1, -1);
    }

    public function testScaleInvalid(): void
    {
        $size = new Size(800, 600);
        $this->expectException(InvalidArgumentException::class);
        $size->scale(-1);
    }

    public function testScaleDownInvalid(): void
    {
        $size = new Size(800, 600);
        $this->expectException(InvalidArgumentException::class);
        $size->scaleDown(-1);
    }

    public function testCoverInvalid(): void
    {
        $size = new Size(800, 600);
        $this->expectException(InvalidArgumentException::class);
        $size->cover(-1, -1);
    }

    public function testContainInvalid(): void
    {
        $size = new Size(800, 600);
        $this->expectException(InvalidArgumentException::class);
        $size->contain(-1, -1);
    }

    public function testContainDownInvalid(): void
    {
        $size = new Size(800, 600);
        $this->expectException(InvalidArgumentException::class);
        $size->containDown(-1, -1);
    }

    public function testCoverDown(): void
    {
        $size = new Size(800, 600);
        $result = $size->cover(400, 400);
        $this->assertGreaterThanOrEqual(400, $result->width());
        $this->assertGreaterThanOrEqual(400, $result->height());
    }

    public function testScaleBothWidthAndHeight(): void
    {
        $size = new Size(800, 600);
        $result = $size->scale(400, 300);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleDownBothWidthAndHeight(): void
    {
        $size = new Size(800, 600);
        $result = $size->scaleDown(400, 300);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleDownHeightOnly(): void
    {
        $size = new Size(800, 600);
        $result = $size->scaleDown(height: 300);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testResizeDownWidthOnly(): void
    {
        $size = new Size(800, 600);
        $result = $size->resizeDown(400);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testResizeDownHeightOnly(): void
    {
        $size = new Size(800, 600);
        $result = $size->resizeDown(height: 300);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testCoverPortraitSource(): void
    {
        // Cover where proportional height check triggers the auto-width branch
        $size = new Size(400, 800);
        $result = $size->cover(200, 200);
        $this->assertGreaterThanOrEqual(200, $result->width());
        $this->assertGreaterThanOrEqual(200, $result->height());
    }

    public function testContainPortrait(): void
    {
        // Contain where auto-height exceeds target so auto-width branch is taken
        $size = new Size(400, 800);
        $result = $size->contain(200, 200);
        $this->assertLessThanOrEqual(200, $result->width());
        $this->assertLessThanOrEqual(200, $result->height());
    }

    public function testContainDownPortrait(): void
    {
        // ContainDown where auto-height exceeds target so auto-width branch is taken
        $size = new Size(400, 800);
        $result = $size->containDown(200, 200);
        $this->assertLessThanOrEqual(200, $result->width());
        $this->assertLessThanOrEqual(200, $result->height());
    }
}
