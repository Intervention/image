<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Tools;

use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Tools\Resizer;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Resizer::class)]
final class ResizerTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $resizer = new Resizer(100, 200);
        $this->assertInstanceOf(Resizer::class, $resizer);
    }

    public function testConstructorWidthOnly(): void
    {
        $resizer = new Resizer(100);
        $this->assertInstanceOf(Resizer::class, $resizer);
    }

    public function testConstructorHeightOnly(): void
    {
        $resizer = new Resizer(height: 200);
        $this->assertInstanceOf(Resizer::class, $resizer);
    }

    public function testConstructorNoArgs(): void
    {
        $resizer = new Resizer();
        $this->assertInstanceOf(Resizer::class, $resizer);
    }

    public function testConstructorInvalidWidth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Resizer(0);
    }

    public function testConstructorInvalidWidthNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Resizer(-1);
    }

    public function testConstructorInvalidHeight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Resizer(height: 0);
    }

    public function testConstructorInvalidHeightNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Resizer(height: -5);
    }

    public function testStaticTo(): void
    {
        $resizer = Resizer::to(100, 200);
        $this->assertInstanceOf(Resizer::class, $resizer);
    }

    public function testToWidth(): void
    {
        $resizer = new Resizer();
        $result = $resizer->toWidth(300);
        $this->assertSame($resizer, $result);
    }

    public function testToHeight(): void
    {
        $resizer = new Resizer();
        $result = $resizer->toHeight(400);
        $this->assertSame($resizer, $result);
    }

    public function testToSize(): void
    {
        $resizer = new Resizer();
        $size = new Size(500, 300);
        $result = $resizer->toSize($size);
        $this->assertSame($resizer, $result);
    }

    public function testResizeWithBothDimensions(): void
    {
        $resizer = new Resizer(200, 100);
        $original = new Size(800, 600);
        $result = $resizer->resize($original);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(100, $result->height());
    }

    public function testResizeWidthOnly(): void
    {
        $resizer = new Resizer(200);
        $original = new Size(800, 600);
        $result = $resizer->resize($original);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testResizeHeightOnly(): void
    {
        $resizer = new Resizer(height: 100);
        $original = new Size(800, 600);
        $result = $resizer->resize($original);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(100, $result->height());
    }

    public function testResizeNoTarget(): void
    {
        $resizer = new Resizer();
        $original = new Size(800, 600);
        $result = $resizer->resize($original);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testResizeDown(): void
    {
        $resizer = new Resizer(200, 100);
        $original = new Size(800, 600);
        $result = $resizer->resizeDown($original);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(100, $result->height());
    }

    public function testResizeDownDoesNotUpscale(): void
    {
        $resizer = new Resizer(1000, 800);
        $original = new Size(400, 300);
        $result = $resizer->resizeDown($original);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testResizeDownWidthOnly(): void
    {
        $resizer = new Resizer(200);
        $original = new Size(800, 600);
        $result = $resizer->resizeDown($original);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testResizeDownHeightOnly(): void
    {
        $resizer = new Resizer(height: 100);
        $original = new Size(800, 600);
        $result = $resizer->resizeDown($original);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(100, $result->height());
    }

    public function testScaleWithBothDimensions(): void
    {
        $resizer = new Resizer(400, 300);
        $original = new Size(800, 600);
        $result = $resizer->scale($original);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleWidthOnly(): void
    {
        $resizer = new Resizer(400);
        $original = new Size(800, 600);
        $result = $resizer->scale($original);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleHeightOnly(): void
    {
        $resizer = new Resizer(height: 300);
        $original = new Size(800, 600);
        $result = $resizer->scale($original);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleNoTarget(): void
    {
        $resizer = new Resizer();
        $original = new Size(800, 600);
        $result = $resizer->scale($original);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testScaleDownWithBothDimensions(): void
    {
        $resizer = new Resizer(400, 300);
        $original = new Size(800, 600);
        $result = $resizer->scaleDown($original);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleDownDoesNotUpscale(): void
    {
        $resizer = new Resizer(1000, 800);
        $original = new Size(400, 300);
        $result = $resizer->scaleDown($original);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleDownWidthOnly(): void
    {
        $resizer = new Resizer(200);
        $original = new Size(800, 600);
        $result = $resizer->scaleDown($original);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(150, $result->height());
    }

    public function testScaleDownWidthOnlyDoesNotUpscale(): void
    {
        $resizer = new Resizer(1000);
        $original = new Size(400, 300);
        $result = $resizer->scaleDown($original);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleDownHeightOnly(): void
    {
        $resizer = new Resizer(height: 150);
        $original = new Size(800, 600);
        $result = $resizer->scaleDown($original);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(150, $result->height());
    }

    public function testScaleDownHeightOnlyDoesNotUpscale(): void
    {
        $resizer = new Resizer(height: 1000);
        $original = new Size(400, 300);
        $result = $resizer->scaleDown($original);
        $this->assertEquals(400, $result->width());
        $this->assertEquals(300, $result->height());
    }

    public function testScaleDownNoTarget(): void
    {
        $resizer = new Resizer();
        $original = new Size(800, 600);
        $result = $resizer->scaleDown($original);
        $this->assertEquals(800, $result->width());
        $this->assertEquals(600, $result->height());
    }

    public function testCover(): void
    {
        $resizer = new Resizer(200, 200);
        $original = new Size(800, 600);
        $result = $resizer->cover($original);
        // cover should scale up so the image covers the target
        $this->assertGreaterThanOrEqual(200, $result->width());
        $this->assertGreaterThanOrEqual(200, $result->height());
    }

    public function testCoverLandscapeIntoPortrait(): void
    {
        $resizer = new Resizer(100, 200);
        $original = new Size(800, 600);
        $result = $resizer->cover($original);
        $this->assertGreaterThanOrEqual(100, $result->width());
        $this->assertGreaterThanOrEqual(200, $result->height());
    }

    public function testContain(): void
    {
        $resizer = new Resizer(200, 200);
        $original = new Size(800, 600);
        $result = $resizer->contain($original);
        // contain should fit into target
        $this->assertLessThanOrEqual(200, $result->width());
        $this->assertLessThanOrEqual(200, $result->height());
    }

    public function testContainLandscape(): void
    {
        $resizer = new Resizer(400, 200);
        $original = new Size(800, 600);
        $result = $resizer->contain($original);
        $this->assertLessThanOrEqual(400, $result->width());
        $this->assertLessThanOrEqual(200, $result->height());
    }

    public function testContainDown(): void
    {
        $resizer = new Resizer(200, 200);
        $original = new Size(800, 600);
        $result = $resizer->containDown($original);
        $this->assertLessThanOrEqual(200, $result->width());
        $this->assertLessThanOrEqual(200, $result->height());
    }

    public function testContainDownDoesNotUpscale(): void
    {
        $resizer = new Resizer(1000, 1000);
        $original = new Size(400, 300);
        $result = $resizer->containDown($original);
        $this->assertLessThanOrEqual(400, $result->width());
        $this->assertLessThanOrEqual(300, $result->height());
    }

    public function testContainDownTallTarget(): void
    {
        // Force the branch where auto-height doesn't fit into target
        $resizer = new Resizer(400, 100);
        $original = new Size(800, 600);
        $result = $resizer->containDown($original);
        $this->assertLessThanOrEqual(400, $result->width());
        $this->assertLessThanOrEqual(100, $result->height());
    }

    public function testCrop(): void
    {
        $resizer = new Resizer(200, 200);
        $original = new Size(800, 600);
        $result = $resizer->crop($original);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(200, $result->height());
    }

    public function testCropWithAlignment(): void
    {
        $resizer = new Resizer(200, 200);
        $original = new Size(800, 600);
        $result = $resizer->crop($original, Alignment::CENTER);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(200, $result->height());
    }

    public function testCropWithStringAlignment(): void
    {
        $resizer = new Resizer(200, 200);
        $original = new Size(800, 600);
        $result = $resizer->crop($original, 'bottom-right');
        $this->assertEquals(200, $result->width());
        $this->assertEquals(200, $result->height());
    }

    public function testTargetSizeThrowsWithoutBothDimensions(): void
    {
        // cover() calls targetSize() internally, so calling cover without both dimensions throws
        $resizer = new Resizer(200);
        $original = new Size(800, 600);
        $this->expectException(StateException::class);
        $resizer->cover($original);
    }

    public function testScaleNonProportional(): void
    {
        // Test where both target dimensions constrain, and proportional width differs from target width
        $resizer = new Resizer(100, 200);
        $original = new Size(800, 400);
        $result = $resizer->scale($original);
        // aspect ratio is 2:1, target is 100x200
        // proportionalWidth = 200 * 2 = 400, min(400, 100) = 100
        // proportionalHeight = 100 / 2 = 50, min(50, 200) = 50
        $this->assertEquals(100, $result->width());
        $this->assertEquals(50, $result->height());
    }

    public function testScaleDownBothConstrainedSmaller(): void
    {
        // Both dimensions given, but original is already smaller — scaleDown should not upscale
        $resizer = new Resizer(500, 500);
        $original = new Size(200, 100);
        $result = $resizer->scaleDown($original);
        $this->assertEquals(200, $result->width());
        $this->assertEquals(100, $result->height());
    }
}
