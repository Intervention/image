<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Factories\BezierFactory;
use Intervention\Image\Geometry\Factories\Drawable;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BezierFactory::class)]
final class DrawableTest extends BaseTestCase
{
    public function testBezier(): void
    {
        $this->assertInstanceOf(Bezier::class, Drawable::bezier());
    }

    public function testCircle(): void
    {
        $this->assertInstanceOf(Circle::class, Drawable::circle());
    }

    public function testEllipse(): void
    {
        $this->assertInstanceOf(Ellipse::class, Drawable::ellipse());
    }

    public function testLine(): void
    {
        $this->assertInstanceOf(Line::class, Drawable::line());
    }

    public function testPolygon(): void
    {
        $this->assertInstanceOf(Polygon::class, Drawable::polygon());
    }

    public function testRectangle(): void
    {
        $this->assertInstanceOf(Rectangle::class, Drawable::rectangle());
    }
}
