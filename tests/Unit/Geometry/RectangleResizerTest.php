<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Geometry\Tools\RectangleResizer;
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

    #[DataProvider('resizeDataProvider')]
    public function testResize(Rectangle $input, array $resizeParameters, Rectangle $result): void
    {
        $resizer = new RectangleResizer(...$resizeParameters);
        $resized = $resizer->resize($input);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    public static function resizeDataProvider(): Generator
    {
        yield [new Rectangle(300, 200), ['width' => 150], new Rectangle(150, 200)];
        yield [new Rectangle(300, 200), ['height' => 150], new Rectangle(300, 150)];
        yield [new Rectangle(300, 200), ['width' => 20, 'height' => 10], new Rectangle(20, 10)];
        yield [new Rectangle(300, 200), [], new Rectangle(300, 200)];
    }

    #[DataProvider('resizeDownDataProvider')]
    public function testResizeDown(Rectangle $input, array $resizeParameters, Rectangle $result): void
    {
        $resizer = new RectangleResizer(...$resizeParameters);
        $resized = $resizer->resizeDown($input);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    public static function resizeDownDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 2000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 1000], new Rectangle(400, 600)];
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 400], new Rectangle(800, 400)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 300], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 1000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['height' => 1000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), [], new Rectangle(800, 600)];
    }

    #[DataProvider('scaleDataProvider')]
    public function testScale(Rectangle $input, array $resizeParameters, Rectangle $result): void
    {
        $resizer = new RectangleResizer(...$resizeParameters);
        $resized = $resizer->scale($input);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    public static function scaleDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 2000], new Rectangle(1000, 750)];
        yield [new Rectangle(800, 600), ['width' => 2000, 'height' => 1000], new Rectangle(1333, 1000)];
        yield [new Rectangle(800, 600), ['height' => 3000], new Rectangle(4000, 3000)];
        yield [new Rectangle(800, 600), ['width' => 8000], new Rectangle(8000, 6000)];
        yield [new Rectangle(800, 600), ['width' => 100, 'height' => 400], new Rectangle(100, 75)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 100], new Rectangle(133, 100)];
        yield [new Rectangle(800, 600), ['height' => 300], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 80], new Rectangle(80, 60)];
        yield [new Rectangle(640, 480), ['width' => 225], new Rectangle(225, 169)];
        yield [new Rectangle(640, 480), ['width' => 223], new Rectangle(223, 167)];
        yield [new Rectangle(600, 800), ['width' => 300, 'height' => 300], new Rectangle(225, 300)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 10], new Rectangle(13, 10)];
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 1200], new Rectangle(1000, 750)];
        yield [new Rectangle(12000, 12), ['width' => 4000, 'height' => 3000], new Rectangle(4000, 4)];
        yield [new Rectangle(12, 12000), ['width' => 4000, 'height' => 3000], new Rectangle(3, 3000)];
        yield [new Rectangle(12000, 6000), ['width' => 4000, 'height' => 3000], new Rectangle(4000, 2000)];
        yield [new Rectangle(3, 3000), ['height' => 300], new Rectangle(1, 300)];
        yield [new Rectangle(800, 600), [], new Rectangle(800, 600)];
    }

    #[DataProvider('scaleDownDataProvider')]
    public function testScaleDown(Rectangle $input, array $resizeParameters, Rectangle $result): void
    {
        $resizer = new RectangleResizer(...$resizeParameters);
        $resized = $resizer->scaleDown($input);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    public static function scaleDownDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 2000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 600], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['width' => 1000, 'height' => 300], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 1000], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 400], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['height' => 300], new Rectangle(400, 300)];
        yield [new Rectangle(800, 600), ['width' => 1000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['height' => 1000], new Rectangle(800, 600)];
        yield [new Rectangle(800, 600), ['width' => 100], new Rectangle(100, 75)];
        yield [new Rectangle(800, 600), ['width' => 300, 'height' => 200], new Rectangle(267, 200)];
        yield [new Rectangle(600, 800), ['width' => 300, 'height' => 300], new Rectangle(225, 300)];
        yield [new Rectangle(800, 600), ['width' => 400, 'height' => 10], new Rectangle(13, 10)];
        yield [new Rectangle(3, 3000), ['height' => 300], new Rectangle(1, 300)];
        yield [new Rectangle(800, 600), [], new Rectangle(800, 600)];
    }

    #[DataProvider('coverDataProvider')]
    public function testCover(Rectangle $origin, Rectangle $target, Rectangle $result): void
    {
        $resizer = new RectangleResizer();
        $resizer->toSize($target);
        $resized = $resizer->cover($origin);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    public static function coverDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), new Rectangle(100, 100), new Rectangle(133, 100)];
        yield [new Rectangle(800, 600), new Rectangle(200, 100), new Rectangle(200, 150)];
        yield [new Rectangle(800, 600), new Rectangle(100, 200), new Rectangle(267, 200)];
        yield [new Rectangle(800, 600), new Rectangle(2000, 10), new Rectangle(2000, 1500)];
        yield [new Rectangle(800, 600), new Rectangle(10, 2000), new Rectangle(2667, 2000)];
        yield [new Rectangle(800, 600), new Rectangle(800, 600), new Rectangle(800, 600)];
        yield [new Rectangle(400, 300), new Rectangle(120, 120), new Rectangle(160, 120)];
        yield [new Rectangle(600, 800), new Rectangle(100, 100), new Rectangle(100, 133)];
        yield [new Rectangle(100, 100), new Rectangle(800, 600), new Rectangle(800, 800)];
    }

    #[DataProvider('containDataProvider')]
    public function testContain(Rectangle $origin, Rectangle $target, Rectangle $result): void
    {
        $resizer = new RectangleResizer();
        $resizer->toSize($target);
        $resized = $resizer->contain($origin);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
    }

    public static function containDataProvider(): Generator
    {
        yield [new Rectangle(800, 600), new Rectangle(100, 100), new Rectangle(100, 75)];
        yield [new Rectangle(800, 600), new Rectangle(200, 100), new Rectangle(133, 100)];
        yield [new Rectangle(800, 600), new Rectangle(100, 200), new Rectangle(100, 75)];
        yield [new Rectangle(800, 600), new Rectangle(2000, 10), new Rectangle(13, 10)];
        yield [new Rectangle(800, 600), new Rectangle(10, 2000), new Rectangle(10, 8)];
        yield [new Rectangle(800, 600), new Rectangle(800, 600), new Rectangle(800, 600)];
        yield [new Rectangle(400, 300), new Rectangle(120, 120), new Rectangle(120, 90)];
        yield [new Rectangle(600, 800), new Rectangle(100, 100), new Rectangle(75, 100)];
        yield [new Rectangle(100, 100), new Rectangle(800, 600), new Rectangle(600, 600)];
    }

    #[DataProvider('cropDataProvider')]
    public function testCrop(Rectangle $origin, Rectangle $target, string $position, Rectangle $result): void
    {
        $resizer = new RectangleResizer();
        $resizer->toSize($target);
        $resized = $resizer->crop($origin, $position);
        $this->assertEquals($result->width(), $resized->width());
        $this->assertEquals($result->height(), $resized->height());
        $this->assertEquals($result->pivot()->x(), $resized->pivot()->x());
        $this->assertEquals($result->pivot()->y(), $resized->pivot()->y());
    }

    public static function cropDataProvider(): Generator
    {
        yield [
            new Rectangle(800, 600),
            new Rectangle(100, 100),
            'center',
            new Rectangle(100, 100, new Point(350, 250))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(200, 100),
            'center',
            new Rectangle(200, 100, new Point(300, 250))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(100, 200),
            'center',
            new Rectangle(100, 200, new Point(350, 200))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(2000, 10),
            'center',
            new Rectangle(2000, 10, new Point(-600, 295))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(10, 2000),
            'center',
            new Rectangle(10, 2000, new Point(395, -700))
        ];
        yield [
            new Rectangle(800, 600),
            new Rectangle(800, 600),
            'center',
            new Rectangle(800, 600, new Point(0, 0))
        ];
        yield [
            new Rectangle(400, 300),
            new Rectangle(120, 120),
            'center',
            new Rectangle(120, 120, new Point(140, 90))
        ];
        yield [
            new Rectangle(600, 800),
            new Rectangle(100, 100),
            'center',
            new Rectangle(100, 100, new Point(250, 350))
        ];
    }
}
