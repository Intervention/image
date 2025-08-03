<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Alignment;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Geometry\Tools\RectangleResizer;
use Intervention\Image\Tests\Providers\ResizeDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

#[CoversClass(RectangleResizer::class)]
final class RectangleResizerTest extends TestCase
{
    public function testMake(): void
    {
        $resizer = RectangleResizer::to();
        $this->assertInstanceOf(RectangleResizer::class, $resizer);

        $resizer = RectangleResizer::to(height: 100);
        $this->assertInstanceOf(RectangleResizer::class, $resizer);

        $resizer = RectangleResizer::to(100);
        $this->assertInstanceOf(RectangleResizer::class, $resizer);

        $resizer = RectangleResizer::to(100, 100);
        $this->assertInstanceOf(RectangleResizer::class, $resizer);
    }

    public function testToWidth(): void
    {
        $resizer = new RectangleResizer();
        $result = $resizer->toWidth(100);
        $this->assertInstanceOf(RectangleResizer::class, $result);
    }

    public function testToHeight(): void
    {
        $resizer = new RectangleResizer();
        $result = $resizer->toHeight(100);
        $this->assertInstanceOf(RectangleResizer::class, $result);
    }

    public function testToSize(): void
    {
        $resizer = new RectangleResizer();
        $resizer = $resizer->toSize(new Rectangle(200, 100));
        $this->assertInstanceOf(RectangleResizer::class, $resizer);
    }

    /**
     * @param $resizeParameters array<string, int>
     */
    #[DataProviderExternal(ResizeDataProvider::class, 'resizeDataProvider')]
    public function testResize(Rectangle $input, array $resizeParameters, Rectangle $result): void
    {
        $resizer = new RectangleResizer(...$resizeParameters);
        $resized = $resizer->resize($input);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    /**
     * @param $resizeParameters array<string, int>
     */
    #[DataProviderExternal(ResizeDataProvider::class, 'resizeDownDataProvider')]
    public function testResizeDown(Rectangle $input, array $resizeParameters, Rectangle $result): void
    {
        $resizer = new RectangleResizer(...$resizeParameters);
        $resized = $resizer->resizeDown($input);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    /**
     * @param $resizeParameters array<string, int>
     */
    #[DataProviderExternal(ResizeDataProvider::class, 'scaleDataProvider')]
    public function testScale(Rectangle $input, array $resizeParameters, Rectangle $result): void
    {
        $resizer = new RectangleResizer(...$resizeParameters);
        $resized = $resizer->scale($input);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    /**
     * @param $resizeParameters array<string, int>
     */
    #[DataProviderExternal(ResizeDataProvider::class, 'scaleDownDataProvider')]
    public function testScaleDown(Rectangle $input, array $resizeParameters, Rectangle $result): void
    {
        $resizer = new RectangleResizer(...$resizeParameters);
        $resized = $resizer->scaleDown($input);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    #[DataProviderExternal(ResizeDataProvider::class, 'coverDataProvider')]
    public function testCover(Rectangle $origin, Rectangle $target, Rectangle $result): void
    {
        $resizer = new RectangleResizer();
        $resizer->toSize($target);
        $resized = $resizer->cover($origin);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    #[DataProviderExternal(ResizeDataProvider::class, 'containDataProvider')]
    public function testContain(Rectangle $origin, Rectangle $target, Rectangle $result): void
    {
        $resizer = new RectangleResizer();
        $resizer->toSize($target);
        $resized = $resizer->contain($origin);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    #[DataProviderExternal(ResizeDataProvider::class, 'cropDataProvider')]
    public function testCrop(Rectangle $origin, Rectangle $target, string|Alignment $position, Rectangle $result): void
    {
        $resizer = new RectangleResizer();
        $resizer->toSize($target);
        $resized = $resizer->crop($origin, $position);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
        $this->assertEquals($result->pivot()->x(), $resized->pivot()->x());
        $this->assertEquals($result->pivot()->y(), $resized->pivot()->y());
    }
}
