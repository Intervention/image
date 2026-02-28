<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Factories\EllipseFactory;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Ellipse::class)]
final class EllipseTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $this->assertInstanceOf(Ellipse::class, $ellipse);
        $this->assertEquals(10, $ellipse->width());
        $this->assertEquals(20, $ellipse->height());
    }

    public function testPosition(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $this->assertInstanceOf(Point::class, $ellipse->position());
        $this->assertEquals(100, $ellipse->position()->x());
        $this->assertEquals(200, $ellipse->position()->y());

        $this->assertInstanceOf(Point::class, $ellipse->pivot());
        $this->assertEquals(100, $ellipse->pivot()->x());
        $this->assertEquals(200, $ellipse->pivot()->y());
    }

    public function testSetPosition(): void
    {
        $ellipse = new Ellipse(10, 20);
        $this->assertEquals(0, $ellipse->position()->x());
        $this->assertEquals(0, $ellipse->position()->y());

        $result = $ellipse->setPosition(new Point(50, 60));
        $this->assertInstanceOf(Ellipse::class, $result);
        $this->assertEquals(50, $ellipse->position()->x());
        $this->assertEquals(60, $ellipse->position()->y());
    }

    public function testSetSize(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $this->assertEquals(10, $ellipse->width());
        $this->assertEquals(20, $ellipse->height());
        $result = $ellipse->setSize(100, 200);
        $this->assertInstanceOf(Ellipse::class, $result);
        $this->assertEquals(100, $ellipse->width());
        $this->assertEquals(200, $ellipse->height());
    }

    public function testSetWidthHeight(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $this->assertEquals(10, $ellipse->width());
        $this->assertEquals(20, $ellipse->height());
        $result = $ellipse->setWidth(100);
        $this->assertInstanceOf(Ellipse::class, $result);
        $this->assertEquals(100, $ellipse->width());
        $this->assertEquals(20, $ellipse->height());
        $result = $ellipse->setHeight(200);
        $this->assertInstanceOf(Ellipse::class, $result);
        $this->assertEquals(100, $ellipse->width());
        $this->assertEquals(200, $ellipse->height());
    }

    public function testFactory(): void
    {
        $ellipse = new Ellipse(10, 20);
        $factory = $ellipse->factory();
        $this->assertInstanceOf(EllipseFactory::class, $factory);
    }

    public function testClone(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $clone = clone $ellipse;

        $this->assertEquals(100, $clone->pivot()->x());
        $this->assertEquals(200, $clone->pivot()->y());

        // verify deep copy — changing clone should not affect original
        $clone->pivot()->setX(99);
        $this->assertEquals(100, $ellipse->pivot()->x());
        $this->assertEquals(99, $clone->pivot()->x());
    }

    public function testCloneWithColors(): void
    {
        $ellipse = new Ellipse(10, 20, new Point(100, 200));
        $ellipse->setBackgroundColor(new Color(255, 0, 0));
        $ellipse->setBorderColor(new Color(0, 0, 255));

        $clone = clone $ellipse;

        // AbstractColor instances are deep cloned
        $this->assertNotSame($ellipse->backgroundColor(), $clone->backgroundColor());
        $this->assertNotSame($ellipse->borderColor(), $clone->borderColor());
    }
}
