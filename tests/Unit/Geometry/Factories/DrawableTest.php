<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Factories;

use Intervention\Image\Geometry\Factories\BezierFactory;
use Intervention\Image\Geometry\Factories\CircleFactory;
use Intervention\Image\Geometry\Factories\Drawable;
use Intervention\Image\Geometry\Factories\EllipseFactory;
use Intervention\Image\Geometry\Factories\LineFactory;
use Intervention\Image\Geometry\Factories\PolygonFactory;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BezierFactory::class)]
final class DrawableTest extends BaseTestCase
{
    public function testBezier(): void
    {
        $this->assertInstanceOf(BezierFactory::class, Drawable::bezier());
    }

    public function testCircle(): void
    {
        $this->assertInstanceOf(CircleFactory::class, Drawable::circle());
    }

    public function testEllipse(): void
    {
        $this->assertInstanceOf(EllipseFactory::class, Drawable::ellipse());
    }

    public function testLine(): void
    {
        $this->assertInstanceOf(LineFactory::class, Drawable::line());
    }

    public function testPolygon(): void
    {
        $this->assertInstanceOf(PolygonFactory::class, Drawable::polygon());
    }

    public function testRectangle(): void
    {
        $this->assertInstanceOf(RectangleFactory::class, Drawable::rectangle());
    }
}
